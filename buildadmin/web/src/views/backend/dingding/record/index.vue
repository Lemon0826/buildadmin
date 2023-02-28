<template>
    <div class="default-main ba-table-box">
        <el-alert class="ba-table-alert" v-if="baTable.table.remark" :title="baTable.table.remark" type="info" show-icon />

        <!-- 表格顶部菜单 -->
        <TableHeader
            :buttons="['refresh', 'delete', 'comSearch', 'quickSearch', 'columnDisplay']"
            :quick-search-placeholder="
                t('quick Search Placeholder', {
                    fields: t('dingding.record.quick Search Fields'),
                })
            "
        />

        <!-- 表格 -->
        <!-- 要使用`el-table`组件原有的属性，直接加在Table标签上即可 -->
        <Table ref="tableRef" />
    </div>
</template>

<script setup lang="ts">
import { ref, provide, onMounted } from 'vue'
import baTableClass from '/@/utils/baTable'
import { defaultOptButtons } from '/@/components/table'
import { baTableApi } from '/@/api/common'
import { useI18n } from 'vue-i18n'
import Table from '/@/components/table/index.vue'
import TableHeader from '/@/components/table/header/index.vue'

const { t } = useI18n()
const tableRef = ref()
const optButtons = defaultOptButtons(['delete'])
const baTable = new baTableClass(
    new baTableApi('/admin/dingding.record/'),
    {
        pk: 'id',
        column: [
            { type: 'selection', align: 'center', operator: false },
            {
                label: t('dingding.record.id'),
                prop: 'id',
                align: 'center',
                width: 70,
                sortable: 'custom',
                operator: 'RANGE',
            },
            {
                label: t('dingding.record.config_title'),
                prop: 'config_id',
                align: 'center',
                render: 'tag',
                renderFormatter: (row: TableRow, field: TableColumn, value: any, column: TableColumnCtx<TableRow>, index: number) => {
                    return row.config.name
                },
                comSearchRender: 'remoteSelect',
                remote: {
                    pk: 'id',
                    field: 'name',
                    remoteUrl: '/admin/dingding.config/' + 'index',
                },
            },
            {
                label: t('dingding.record.template_title'),
                prop: 'template_id',
                align: 'center',
                render: 'tag',
                renderFormatter: (row: TableRow, field: TableColumn, value: any, column: TableColumnCtx<TableRow>, index: number) => {
                    return row.template.name
                },
                comSearchRender: 'remoteSelect',
                remote: {
                    pk: 'id',
                    field: 'name',
                    remoteUrl: '/admin/dingding.templdate/' + 'index',
                },
            },
            {
                label: t('dingding.record.content'),
                prop: 'content',
                align: 'center',
                'show-overflow-tooltip': true,
            },
            {
                label: t('dingding.record.status'),
                prop: 'status',
                align: 'center',
                render: 'tag',
                replaceValue: {
                    0: t('dingding.record.status 0'),
                    1: t('dingding.record.status 1'),
                },
            },
            {
                label: t('dingding.record.message'),
                prop: 'message',
                align: 'center',
                'show-overflow-tooltip': true,
            },
            {
                label: t('dingding.record.createtime'),
                prop: 'createtime',
                align: 'center',
                render: 'datetime',
                sortable: 'custom',
                operator: 'RANGE',
                width: 160,
            },
            {
                label: t('dingding.record.updatetime'),
                prop: 'updatetime',
                align: 'center',
                render: 'datetime',
                sortable: 'custom',
                operator: false,
                width: 160,
                show: false,
            },
            {
                label: t('operate'),
                align: 'center',
                width: 80,
                render: 'buttons',
                buttons: optButtons,
                operator: false,
            },
        ],
        dblClickNotEditColumn: [undefined],
        defaultOrder: { prop: 'id', order: 'desc' },
    },
    {
        defaultItems: { status: '0', message: 'success' },
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
import { TableColumnCtx } from 'element-plus/es/components/table/src/table-column/defaults'
export default defineComponent({
    name: 'dingding/record',
})
</script>

<style scoped lang="scss"></style>
