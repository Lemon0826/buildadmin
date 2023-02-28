<template>
    <div class="default-main ba-table-box">
        <el-alert class="ba-table-alert" v-if="baTable.table.remark" :title="baTable.table.remark" type="info" show-icon />

        <!-- 表格顶部菜单 -->
        <TableHeader
            :buttons="['refresh', 'add', 'edit', 'delete', 'comSearch', 'quickSearch', 'columnDisplay']"
            :quick-search-placeholder="t('quick Search Placeholder', { fields: t('hmzxCsal.quick Search Fields') })"
        />

        <!-- 表格 -->
        <!-- 要使用`el-table`组件原有的属性，直接加在Table标签上即可 -->
        <Table ref="tableRef" />

        <!-- 表单 -->
        <PopupForm />
    </div>
</template>

<script setup lang="ts">
import { ref, provide, onMounted } from 'vue'
import baTableClass from '/@/utils/baTable'
import { defaultOptButtons } from '/@/components/table'
import { baTableApi } from '/@/api/common'
import { useI18n } from 'vue-i18n'
import PopupForm from './popupForm.vue'
import Table from '/@/components/table/index.vue'
import TableHeader from '/@/components/table/header/index.vue'

const { t } = useI18n()
const tableRef = ref()
const optButtons = defaultOptButtons(['edit', 'delete'])
const baTable = new baTableClass(
    new baTableApi('/admin/HmzxCsal/'),
    {
        pk: 'ID',
        column: [
            { type: 'selection', align: 'center', operator: false },
            { label: t('hmzxCsal.ID'), prop: 'ID', align: 'center', width: 70, operator: 'RANGE', sortable: 'custom' },
            { label: t('hmzxCsal.radio'), prop: 'radio', align: 'center', render: 'tag', operator: '=', sortable: 'custom', replaceValue: { '国内': 'radio 国内', '海外': 'radio 海外' } },
            { label: t('hmzxCsal.terrace'), prop: 'terrace', align: 'center', render: 'tag', operator: '=', sortable: 'custom', replaceValue: { '1.0平台': 'terrace 1.0平台', '1.5平台': 'terrace 1.5平台', '1.5H平台': 'terrace 1.5H平台', '3.0平台': 'terrace 3.0平台' } },
            { label: t('hmzxCsal.function_obj'), prop: 'function_obj', align: 'center', operatorPlaceholder: t('Fuzzy query'), operator: 'LIKE', sortable: 'custom' },
            { label: t('hmzxCsal.case_ID'), prop: 'case_ID', align: 'center', operatorPlaceholder: t('Fuzzy query'), operator: 'LIKE', sortable: false },
            { label: t('hmzxCsal.case_type'), prop: 'case_type', align: 'center', operatorPlaceholder: t('Fuzzy query'), operator: 'LIKE', sortable: false },
            { label: t('hmzxCsal.case_infor'), prop: 'case_infor', align: 'center', operatorPlaceholder: t('Fuzzy query'), operator: 'LIKE', sortable: false },
            { label: t('hmzxCsal.case_pic'), prop: 'case_pic', align: 'center', operatorPlaceholder: t('Fuzzy query'), operator: 'LIKE' },
            { label: t('hmzxCsal.case_stand'), prop: 'case_stand', align: 'center', operatorPlaceholder: t('Fuzzy query'), operator: 'LIKE', sortable: false },
            { label: t('hmzxCsal.case_envir'), prop: 'case_envir', align: 'center', operatorPlaceholder: t('Fuzzy query'), operator: 'LIKE', sortable: false },
            { label: t('hmzxCsal.case_place'), prop: 'case_place', align: 'center', operatorPlaceholder: t('Fuzzy query'), operator: 'LIKE', sortable: false },
            { label: t('hmzxCsal.case_level'), prop: 'case_level', align: 'center', render: 'tag', operator: '=', sortable: 'custom', replaceValue: { P0: 'case_level P0', P1: 'case_level P1', P2: 'case_level P2' } },
            { label: t('hmzxCsal.case_danger'), prop: 'case_danger', align: 'center', render: 'tag', operator: '=', sortable: false, replaceValue: { '低': 'case_danger 低', '中': 'case_danger 中', '高': 'case_danger 高' } },
            { label: t('hmzxCsal.case_mark'), prop: 'case_mark', align: 'center', operatorPlaceholder: t('Fuzzy query'), operator: 'LIKE', sortable: false },
            { label: t('hmzxCsal.case_clor'), prop: 'case_clor', align: 'center', operatorPlaceholder: t('Fuzzy query'), operator: 'LIKE', sortable: false },
            { label: t('hmzxCsal.case_timebuild'), prop: 'case_timebuild', align: 'center', operator: 'RANGE', sortable: false },
            { label: t('hmzxCsal.case_revise'), prop: 'case_revise', align: 'center', operator: 'RANGE', sortable: false },
            { label: t('operate'), align: 'center', width: 100, render: 'buttons', buttons: optButtons, operator: false },
        ],
        dblClickNotEditColumn: [undefined],
        defaultOrder: { prop: 'ID', order: 'asc' },
    },
    {
        defaultItems: { radio: '国内', terrace: '1.0平台', case_level: 'P0', case_danger: '低' },
    }
)

provide('baTable', baTable)

onMounted(() => {
    baTable.table.ref = tableRef.value
    baTable.mount()
    baTable.getIndex()?.then(() => {
        baTable.initSort()
        baTable.dragSort()
    })
})
</script>

<script lang="ts">
import { defineComponent } from 'vue'
export default defineComponent({
    name: 'hmzxCsal',
})
</script>

<style scoped lang="scss"></style>
