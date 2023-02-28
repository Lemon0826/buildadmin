<template>
    <!-- 对话框表单 -->
    <el-dialog class="ba-operate-dialog" :close-on-click-modal="false" :model-value="baTable.form.operate ? true : false" @close="baTable.toggleForm">
        <template #header>
            <div class="title" v-drag="['.ba-operate-dialog', '.el-dialog__header']" v-zoom="'.ba-operate-dialog'">
                {{ baTable.form.operate ? t(baTable.form.operate) : '' }}
            </div>
        </template>
        <el-scrollbar v-loading="baTable.form.loading" class="ba-table-form-scrollbar">
            <div
                class="ba-operate-form"
                :class="'ba-' + baTable.form.operate + '-form'"
                :style="'width: calc(100% - ' + baTable.form.labelWidth! / 2 + 'px)'"
            >
                <el-form
                    v-if="!baTable.form.loading"
                    ref="formRef"
                    @keyup.enter="baTable.onSubmit(formRef)"
                    :model="baTable.form.items"
                    label-position="right"
                    :label-width="baTable.form.labelWidth + 'px'"
                    :rules="rules"
                >
                    <FormItem
                        :label="t('dingding.variable.title')"
                        type="string"
                        v-model="baTable.form.items!.title"
                        prop="title"
                        :input-attr="{ placeholder: t('Please input field', { field: t('dingding.variable.title') }) }"
                    />
                    <FormItem
                        :label="t('dingding.variable.name')"
                        type="string"
                        v-model="baTable.form.items!.name"
                        prop="name"
                        :input-attr="{ placeholder: t('Please input field', { field: t('dingding.variable.name') }) }"
                    />
                    <FormItem
                        :label="t('dingding.variable.value_source')"
                        type="radio"
                        v-model="baTable.form.items!.value_source"
                        prop="value_source"
                        :data="{
                            content: {
                                literal: t('dingding.variable.value_source literal'),
                                func: t('dingding.variable.value_source func'),
                                sql: t('dingding.variable.value_source sql'),
                            },
                        }"
                        :input-attr="{ placeholder: t('Please select field', { field: t('dingding.variable.value_source') }) }"
                    />
                    <FormItem
                        v-if="baTable.form.items!.value_source == 'literal'"
                        :label="t('dingding.variable.value')"
                        type="string"
                        v-model="baTable.form.items!.value"
                        prop="value"
                        :input-attr="{ placeholder: t('Please input field', { field: t('dingding.variable.value') }) }"
                    />
                    <FormItem
                        v-if="baTable.form.items!.value_source == 'sql'"
                        :label="t('dingding.variable.sql')"
                        type="string"
                        v-model="baTable.form.items!.sql"
                        prop="sql"
                        :input-attr="{ placeholder: t('Please input field', { field: t('dingding.variable.sql') }) }"
                        :attr="{
                            'block-help':
                                '可使用 __PREFIX__ 表示数据表前缀，如 SELECT count(id) FROM __PREFIX__user 将在需要时自动使用 Db::query(SQL) 来获取变量值',
                        }"
                    />
                    <template v-if="baTable.form.items!.value_source == 'func'">
                        <FormItem
                            :label="t('dingding.variable.namespace')"
                            type="string"
                            v-model="baTable.form.items!.namespace"
                            prop="namespace"
                            :input-attr="{ placeholder: t('Please input field', { field: t('dingding.variable.namespace') }) }"
                        />
                        <FormItem
                            :label="t('dingding.variable.class')"
                            type="string"
                            v-model="baTable.form.items!.class"
                            prop="class"
                            :input-attr="{ placeholder: t('Please input field', { field: t('dingding.variable.class') }) }"
                        />
                        <FormItem
                            :label="t('dingding.variable.func')"
                            type="string"
                            v-model="baTable.form.items!.func"
                            prop="func"
                            :input-attr="{ placeholder: t('Please input field', { field: t('dingding.variable.func') }) }"
                        />
                        <FormItem
                            :label="t('dingding.variable.param')"
                            type="string"
                            v-model="baTable.form.items!.param"
                            prop="param"
                            :attr="{
                                'block-help': '自动执行对应的方法并传递以上参数来获取变量的值',
                            }"
                            :input-attr="{ placeholder: t('Please input field', { field: t('dingding.variable.param') }) }"
                        />
                    </template>
                    <FormItem
                        :label="t('dingding.variable.status')"
                        type="radio"
                        v-model="baTable.form.items!.status"
                        prop="status"
                        :data="{
                            content: { 0: t('dingding.variable.status 0'), 1: t('dingding.variable.status 1') },
                            childrenAttr: { border: true },
                        }"
                        :input-attr="{ placeholder: t('Please select field', { field: t('dingding.variable.status') }) }"
                    />
                </el-form>
            </div>
        </el-scrollbar>
        <template #footer>
            <div :style="'width: calc(100% - ' + baTable.form.labelWidth! / 1.8 + 'px)'">
                <el-button @click="baTable.toggleForm('')">{{ t('Cancel') }}</el-button>
                <el-button v-blur :loading="baTable.form.submitLoading" @click="baTable.onSubmit(formRef)" type="primary">
                    {{ baTable.form.operateIds && baTable.form.operateIds.length > 1 ? t('Save and edit next item') : t('Save') }}
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script setup lang="ts">
import { reactive, ref, inject } from 'vue'
import { useI18n } from 'vue-i18n'
import type baTableClass from '/@/utils/baTable'
import FormItem from '/@/components/formItem/index.vue'
import type { ElForm, FormItemRule } from 'element-plus'
import { buildValidatorData } from '/@/utils/validate'

const formRef = ref<InstanceType<typeof ElForm>>()
const baTable = inject('baTable') as baTableClass

const { t } = useI18n()

const rules: Partial<Record<string, FormItemRule[]>> = reactive({
    title: [buildValidatorData({ name: 'required', title: t('dingding.variable.title') })],
    name: [buildValidatorData({ name: 'required', title: t('dingding.variable.name') })],
    value_source: [buildValidatorData({ name: 'required', message: t('Please select field', { field: t('dingding.variable.value_source') }) })],
    value: [buildValidatorData({ name: 'required', title: t('dingding.variable.value') })],
    sql: [buildValidatorData({ name: 'required', title: t('dingding.variable.sql') })],
    namespace: [buildValidatorData({ name: 'required', title: t('dingding.variable.namespace') })],
    class: [buildValidatorData({ name: 'required', title: t('dingding.variable.class') })],
    func: [buildValidatorData({ name: 'required', title: t('dingding.variable.func') })],
    param: [buildValidatorData({ name: 'required', title: t('dingding.variable.param') })],
    status: [buildValidatorData({ name: 'required', message: t('Please select field', { field: t('dingding.variable.status') }) })],
})
</script>

<style scoped lang="scss"></style>
