<template>
    <!-- 对话框表单 -->
    <el-dialog
        class="ba-operate-dialog"
        :close-on-click-modal="false"
        :model-value="['add', 'edit'].includes(baTable.form.operate!)"
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
                    @submit.prevent=""
                    @keyup.enter="baTable.onSubmit(formRef)"
                    :model="baTable.form.items"
                    label-position="right"
                    :label-width="baTable.form.labelWidth + 'px'"
                    :rules="rules"
                >
                    <FormItem :label="t('hmzxCsal.ID')" type="string" v-model="baTable.form.items!.ID" prop="ID" :placeholder="t('Please input field', { field: t('hmzxCsal.ID') })" />
                    <FormItem :label="t('hmzxCsal.radio')" type="radio" v-model="baTable.form.items!.radio" prop="radio" :data="{ content: { '国内': 'radio 国内', '海外': 'radio 海外' } }" :placeholder="t('Please select field', { field: t('hmzxCsal.radio') })" />
                    <FormItem :label="t('hmzxCsal.terrace')" type="radio" v-model="baTable.form.items!.terrace" prop="terrace" :data="{ content: { '1.0平台': 'terrace 1.0平台', '1.5平台': 'terrace 1.5平台', '1.5H平台': 'terrace 1.5H平台', '3.0平台': 'terrace 3.0平台' } }" :placeholder="t('Please select field', { field: t('hmzxCsal.terrace') })" />
                    <FormItem :label="t('hmzxCsal.function_obj')" type="string" v-model="baTable.form.items!.function_obj" prop="function_obj" :placeholder="t('Please input field', { field: t('hmzxCsal.function_obj') })" />
                    <FormItem :label="t('hmzxCsal.case_ID')" type="string" v-model="baTable.form.items!.case_ID" prop="case_ID" :placeholder="t('Please input field', { field: t('hmzxCsal.case_ID') })" />
                    <FormItem :label="t('hmzxCsal.case_type')" type="string" v-model="baTable.form.items!.case_type" prop="case_type" :placeholder="t('Please input field', { field: t('hmzxCsal.case_type') })" />
                    <FormItem :label="t('hmzxCsal.case_infor')" type="string" v-model="baTable.form.items!.case_infor" prop="case_infor" :placeholder="t('Please input field', { field: t('hmzxCsal.case_infor') })" />
                    <FormItem :label="t('hmzxCsal.case_pic')" type="image" v-model="baTable.form.items!.case_pic" prop="case_pic" />
                    <FormItem :label="t('hmzxCsal.case_stand')" type="string" v-model="baTable.form.items!.case_stand" prop="case_stand" :placeholder="t('Please input field', { field: t('hmzxCsal.case_stand') })" />
                    <FormItem :label="t('hmzxCsal.case_envir')" type="string" v-model="baTable.form.items!.case_envir" prop="case_envir" :placeholder="t('Please input field', { field: t('hmzxCsal.case_envir') })" />
                    <FormItem :label="t('hmzxCsal.case_place')" type="string" v-model="baTable.form.items!.case_place" prop="case_place" :placeholder="t('Please input field', { field: t('hmzxCsal.case_place') })" />
                    <FormItem :label="t('hmzxCsal.case_level')" type="radio" v-model="baTable.form.items!.case_level" prop="case_level" :data="{ content: { P0: 'case_level P0', P1: 'case_level P1', P2: 'case_level P2' } }" :placeholder="t('Please select field', { field: t('hmzxCsal.case_level') })" />
                    <FormItem :label="t('hmzxCsal.case_danger')" type="radio" v-model="baTable.form.items!.case_danger" prop="case_danger" :data="{ content: { '低': 'case_danger 低', '中': 'case_danger 中', '高': 'case_danger 高' } }" :placeholder="t('Please select field', { field: t('hmzxCsal.case_danger') })" />
                    <FormItem :label="t('hmzxCsal.case_mark')" type="string" v-model="baTable.form.items!.case_mark" prop="case_mark" :placeholder="t('Please input field', { field: t('hmzxCsal.case_mark') })" />
                    <FormItem :label="t('hmzxCsal.case_clor')" type="string" v-model="baTable.form.items!.case_clor" prop="case_clor" :placeholder="t('Please input field', { field: t('hmzxCsal.case_clor') })" />
                    <FormItem :label="t('hmzxCsal.case_timebuild')" type="number" prop="case_timebuild" :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.case_timebuild" :placeholder="t('Please input field', { field: t('hmzxCsal.case_timebuild') })" />
                    <FormItem :label="t('hmzxCsal.case_revise')" type="number" prop="case_revise" :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.case_revise" :placeholder="t('Please input field', { field: t('hmzxCsal.case_revise') })" />
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
    radio: [buildValidatorData({ name: 'required', title: t('hmzxCsal.radio') })],
    terrace: [buildValidatorData({ name: 'required', title: t('hmzxCsal.terrace') })],
    function_obj: [buildValidatorData({ name: 'required', title: t('hmzxCsal.function_obj') })],
    case_ID: [buildValidatorData({ name: 'required', title: t('hmzxCsal.case_ID') })],
    case_type: [buildValidatorData({ name: 'required', title: t('hmzxCsal.case_type') })],
    case_infor: [buildValidatorData({ name: 'required', title: t('hmzxCsal.case_infor') })],
    case_stand: [buildValidatorData({ name: 'required', title: t('hmzxCsal.case_stand') })],
    case_envir: [buildValidatorData({ name: 'required', title: t('hmzxCsal.case_envir') })],
    case_place: [buildValidatorData({ name: 'required', title: t('hmzxCsal.case_place') })],
    case_level: [buildValidatorData({ name: 'required', title: t('hmzxCsal.case_level') })],
    case_danger: [buildValidatorData({ name: 'required', title: t('hmzxCsal.case_danger') })],
    case_timebuild: [buildValidatorData({ name: 'number', title: t('hmzxCsal.case_timebuild') })],
    case_revise: [buildValidatorData({ name: 'number', title: t('hmzxCsal.case_revise') })],
})
</script>

<style scoped lang="scss"></style>
