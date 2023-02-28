<template>
    <div class="default-main ba-table-box">
        <el-alert class="ba-table-alert" v-if="baTable.table.remark" :title="baTable.table.remark" type="info" show-icon />

        <!-- 表格顶部菜单 -->
        <TableHeader
            :buttons="['refresh', 'add', 'edit', 'delete', 'comSearch', 'quickSearch', 'columnDisplay']"
            :quick-search-placeholder="
                t('quick Search Placeholder', {
                    fields: t('dingding.variable.quick Search Fields'),
                })
            "
        />

        <!-- 表格 -->
        <!-- 要使用`el-table`组件原有的属性，直接加在Table标签上即可 -->
        <Table ref="tableRef" />

        <!-- 表单 -->
        <PopupForm />
    </div>
</template>

<script setup lang="ts">
import { ref, provide, onMounted, h, resolveComponent, markRaw } from 'vue'
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
const renderValue = {
    render(context: TableRenderPublicInstance) {
        if (context.$attrs.renderValue) {
            return h('span', context.$attrs.renderValue)
        }
        const calcVar = () => {
            getVar(context.$attrs.renderRow['id']).then((res) => {
                ElMessageBox.alert(res.data, '计算结果', {
                    confirmButtonText: '确定',
                })
            })
        }
        return h(resolveComponent('el-link'), { type: 'primary', onClick: calcVar }, () => '点击计算')
    },
}
const baTable = new baTableClass(
    new baTableApi('/admin/dingding.variable/'),
    {
        pk: 'id',
        column: [
            { type: 'selection', align: 'center', operator: false },
            {
                label: t('dingding.variable.id'),
                prop: 'id',
                align: 'center',
                width: 70,
                sortable: 'custom',
                operator: 'RANGE',
            },
            { label: t('dingding.variable.title'), prop: 'title', align: 'center' },
            { label: t('dingding.variable.name'), prop: 'name', align: 'center' },
            {
                label: t('dingding.variable.value_source'),
                prop: 'value_source',
                align: 'center',
                render: 'tag',
                replaceValue: {
                    literal: t('dingding.variable.value_source literal'),
                    func: t('dingding.variable.value_source func'),
                    sql: t('dingding.variable.value_source sql'),
                },
            },
            {
                label: t('dingding.variable.value'),
                prop: 'value',
                align: 'center',
                render: 'customRender',
                customRender: markRaw(renderValue),
                'show-overflow-tooltip': true,
            },
            { label: t('dingding.variable.sql'), prop: 'sql', align: 'center' },
            {
                label: t('dingding.variable.namespace'),
                prop: 'namespace',
                align: 'center',
            },
            { label: t('dingding.variable.class'), prop: 'class', align: 'center' },
            { label: t('dingding.variable.func'), prop: 'func', align: 'center' },
            { label: t('dingding.variable.param'), prop: 'param', align: 'center' },
            {
                label: t('dingding.variable.status'),
                prop: 'status',
                align: 'center',
                render: 'tag',
                replaceValue: {
                    0: t('dingding.variable.status 0'),
                    1: t('dingding.variable.status 1'),
                },
            },
            {
                label: t('dingding.variable.updatetime'),
                prop: 'updatetime',
                align: 'center',
                render: 'datetime',
                sortable: 'custom',
                operator: 'RANGE',
                width: 160,
            },
            {
                label: t('dingding.variable.createtime'),
                prop: 'createtime',
                align: 'center',
                render: 'datetime',
                sortable: 'custom',
                operator: 'RANGE',
                width: 160,
            },
            {
                label: t('operate'),
                align: 'center',
                width: 100,
                render: 'buttons',
                buttons: optButtons,
                operator: false,
            },
        ],
        dblClickNotEditColumn: [undefined],
        defaultOrder: { prop: 'id', order: 'desc' },
    },
    {
        defaultItems: { value_source: 'literal', status: '1' },
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
import { ElMessageBox } from 'element-plus'
import { getVar } from '/@/api/backend/dingding/variable'
export default defineComponent({
    name: 'dingding/variable',
})
</script>

<style scoped lang="scss"></style>
