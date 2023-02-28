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
                        :label="t('dingding.config.name')"
                        type="string"
                        v-model="baTable.form.items!.name"
                        prop="name"
                        :input-attr="{ placeholder: t('Please input field', { field: t('dingding.config.name') }) }"
                    />
                    <FormItem
                        :label="t('dingding.config.webhook')"
                        type="string"
                        v-model="baTable.form.items!.webhook"
                        prop="webhook"
                        :input-attr="{ placeholder: t('Please input field', { field: t('dingding.config.webhook') }) }"
                    />
                    <FormItem
                        :label="t('dingding.config.security')"
                        type="radio"
                        v-model="baTable.form.items!.security"
                        prop="security"
                        :data="{
                            content: {
                                sign: t('dingding.config.security sign'),
                                ip: t('dingding.config.security ip'),
                                none: t('dingding.config.security none'),
                            },
                        }"
                        :input-attr="{ placeholder: t('Please select field', { field: t('dingding.config.security') }) }"
                    />
                    <FormItem
                        v-if="baTable.form.items!.security !== 'none' && baTable.form.items!.security !== 'ip'"
                        :label="t('dingding.config.security_value')"
                        type="string"
                        v-model="baTable.form.items!.security_value"
                        prop="security_value"
                        :input-attr="{ placeholder: t('Please input field', { field: t('dingding.config.security_value') }) }"
                    />
                    <FormItem
                        :label="t('dingding.config.default_switch')"
                        type="switch"
                        v-model="baTable.form.items!.default_switch"
                        prop="default_switch"
                        :input-attr="{ placeholder: t('Please input field', { field: t('dingding.config.default_switch') }) }"
                    />
                    <FormItem
                        :label="t('dingding.config.status')"
                        type="radio"
                        v-model="baTable.form.items!.status"
                        prop="status"
                        :data="{ content: { 0: t('dingding.config.status 0'), 1: t('dingding.config.status 1') }, childrenAttr: { border: true } }"
                        :input-attr="{ placeholder: t('Please select field', { field: t('dingding.config.status') }) }"
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
    name: [buildValidatorData({ name: 'required', title: t('dingding.config.name') })],
    webhook: [buildValidatorData({ name: 'required', title: t('dingding.config.webhook') })],
    security: [buildValidatorData({ name: 'required', message: t('Please select field', { field: t('dingding.config.security') }) })],
})
</script>

<style scoped lang="scss"></style>
