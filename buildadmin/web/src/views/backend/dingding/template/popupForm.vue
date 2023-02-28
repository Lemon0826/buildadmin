<template>
    <!-- 对话框表单 -->
    <el-dialog
        class="ba-operate-dialog"
        :close-on-click-modal="false"
        :model-value="baTable.form.operate ? true : false"
        @close="baTable.toggleForm"
        width="50%"
    >
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
                    :model="baTable.form.items"
                    label-position="right"
                    :label-width="baTable.form.labelWidth + 'px'"
                    :rules="rules"
                >
                    <FormItem
                        :label="t('dingding.template.name')"
                        type="string"
                        v-model="baTable.form.items!.name"
                        prop="name"
                        :input-attr="{
                            placeholder: t('Please input field', {
                                field: t('dingding.template.name'),
                            }),
                        }"
                    />
                    <FormItem
                        :label="t('dingding.template.flag')"
                        type="string"
                        v-model="baTable.form.items!.flag"
                        prop="flag"
                        :input-attr="{
                            placeholder: t('Please input field', {
                                field: t('dingding.template.flag'),
                            }),
                        }"
                    />
                    <FormItem
                        :label="t('dingding.template.type')"
                        type="radio"
                        v-model="baTable.form.items!.type"
                        prop="type"
                        :data="{
                            content: {
                                text: t('dingding.template.type text'),
                                markdown: t('dingding.template.type markdown'),
                                actionCardSingle: t('dingding.template.type actionCardSingle'),
                                actionCard: t('dingding.template.type actionCard'),
                                feedCard: t('dingding.template.type feedCard'),
                                link: t('dingding.template.type link'),
                            },
                        }"
                        :attr="{
                            'block-help': '详细参见：https://open.dingtalk.com/document/group/message-types-and-data-format',
                        }"
                        :input-attr="{
                            disabled: baTable.form.operate !== 'add',
                            placeholder: t('Please select field', {
                                field: t('dingding.template.type'),
                            }),
                        }"
                    />
                    <FormItem
                        v-if="baTable.form.items!.type === 'text'"
                        :label="t('dingding.template.content')"
                        type="textarea"
                        v-model="baTable.form.items!.content"
                        prop="content"
                        :input-attr="{
                            autosize: true,
                            placeholder: t('Please input field', {
                                field: t('dingding.template.content'),
                            }),
                        }"
                    />
                    <formVue
                        v-if="baTable.form.items!.type !== 'text'"
                        ref="subFormRef"
                        v-model="baTable.form.items!.content"
                        :type="baTable.form.items!.type"
                        :config="config[baTable.form.items!.type as keyof typeof config]"
                        :label-width="baTable.form.labelWidth + 'px'"
                    ></formVue>
                    <FormItem
                        :label="t('dingding.template.variables')"
                        type="remoteSelect"
                        v-model="baTable.form.items!.variables"
                        prop="variables"
                        :attr="{
                            'block-help': '可在模板内容任何地方使用模板变量：${var_name}',
                        }"
                        :input-attr="{
                            multiple: true,
                            field: 'title',
                            'remote-url': '/admin/dingding.variable/index',
                            pk: 'id',
                            placeholder: t('Please input field', {
                                field: t('dingding.template.variables'),
                            }),
                        }"
                    />
                </el-form>
            </div>
        </el-scrollbar>
        <template #footer>
            <div :style="'width: calc(100% - ' + baTable.form.labelWidth! / 1.8 + 'px)'">
                <el-button @click="baTable.toggleForm('')">{{ t('Cancel') }}</el-button>
                <el-button v-blur :loading="baTable.form.submitLoading" @click="onSubmit(formRef)" type="primary">
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
import formVue, { SubForm } from './components/form.vue'

const formRef = ref<InstanceType<typeof ElForm>>()
const subFormRef = ref<SubForm | null>()
const baTable = inject('baTable') as baTableClass

const { t } = useI18n()

const rules: Partial<Record<string, FormItemRule[]>> = reactive({
    name: [
        buildValidatorData({
            name: 'required',
            title: t('dingding.template.name'),
        }),
    ],
    flag: [
        buildValidatorData({
            name: 'required',
            title: t('dingding.template.flag'),
        }),
    ],
    type: [
        buildValidatorData({
            name: 'required',
            message: t('Please select field', { field: t('dingding.template.type') }),
        }),
    ],
    content: [
        buildValidatorData({
            name: 'editorRequired',
            message: t('Please input field', {
                field: t('dingding.template.content'),
            }),
        }),
    ],
})

const config = {
    markdown: [
        {
            label: '模板标题',
            prop: 'title',
            type: 'string',
            required: true,
        },
        {
            label: '模板内容',
            prop: 'text',
            required: true,
        },
    ],
    actionCardSingle: [
        {
            label: '模板标题',
            prop: 'title',
            type: 'string',
            required: true,
        },
        {
            label: '模板内容',
            prop: 'text',
            required: true,
        },
        {
            label: '按钮标题',
            prop: 'singleTitle',
            type: 'string',
            required: true,
        },
        {
            label: '按钮链接',
            prop: 'singleURL',
            type: 'string',
            required: true,
        },
    ],
    actionCard: [
        {
            label: '模板标题',
            prop: 'title',
            type: 'string',
            required: true,
        },
        {
            label: '模板内容',
            prop: 'text',
            required: true,
        },
        {
            label: '按钮排序',
            prop: 'btnOrientation',
            type: 'radio',
            required: true,
            data: {
                content: {
                    0: '按钮竖直排列',
                    1: '按钮横向排列',
                },
            },
        },
        {
            label: '跳转按钮',
            prop: 'btns',
            required: true,
        },
    ],
    feedCard: [
        {
            label: '链接标题',
            prop: 'title',
            type: 'string',
            required: true,
        },
        {
            label: '消息URL',
            prop: 'messageURL',
            type: 'string',
            required: true,
        },
        {
            label: '图片URL',
            prop: 'picURL',
            type: 'string',
            required: true,
        },
    ],
    link: [
        {
            label: '链接标题',
            prop: 'title',
            type: 'string',
            required: true,
        },
        {
            label: '消息内容',
            prop: 'text',
            type: 'textarea',
            required: true,
            'input-attr': { autosize: true },
        },
        {
            label: '图片URL',
            prop: 'picUrl',
            type: 'textarea',
            'input-attr': { autosize: true },
        },
        {
            label: '消息URL',
            prop: 'messageUrl',
            type: 'textarea',
            required: true,
            'input-attr': { autosize: true },
        },
    ],
}

const onSubmit = (formRef: any) => {
    if (subFormRef.value) {
        subFormRef.value?.formRef.validate((valid: boolean) => {
            if (valid) {
                baTable.onSubmit(formRef)
            }
        })
    } else {
        baTable.onSubmit(formRef)
    }
}
</script>

<style scoped lang="scss"></style>
