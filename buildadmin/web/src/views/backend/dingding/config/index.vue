<template>
    <div class="default-main ba-table-box">
        <el-alert class="ba-table-alert" v-if="baTable.table.remark" :title="baTable.table.remark" type="info" show-icon />

        <!-- 表格顶部菜单 -->
        <TableHeader
            :buttons="['refresh', 'add', 'edit', 'delete', 'comSearch', 'quickSearch', 'columnDisplay']"
            :quick-search-placeholder="
                t('quick Search Placeholder', {
                    fields: t('dingding.config.quick Search Fields'),
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
    new baTableApi('/admin/dingding.config/'),
    {
        pk: 'id',
        column: [
            { type: 'selection', align: 'center', operator: false },
            {
                label: t('dingding.config.id'),
                prop: 'id',
                align: 'center',
                width: 70,
                sortable: 'custom',
                operator: 'RANGE',
            },
            {
                label: t('dingding.config.name'),
                prop: 'name',
                align: 'center',
                operator: 'LIKE',
                operatorPlaceholder: t('Fuzzy query'),
            },
            {
                label: t('dingding.config.webhook'),
                prop: 'webhook',
                align: 'center',
                operator: false,
                'show-overflow-tooltip': true,
            },
            {
                label: t('dingding.config.security'),
                prop: 'security',
                align: 'center',
                render: 'tag',
                replaceValue: {
                    sign: t('dingding.config.security sign'),
                    ip: t('dingding.config.security ip'),
                    none: t('dingding.config.security none'),
                },
            },
            {
                label: t('dingding.config.security_value'),
                prop: 'security_value',
                align: 'center',
                operator: false,
                'show-overflow-tooltip': true,
            },
            {
                label: t('dingding.config.default_switch'),
                prop: 'default_switch',
                align: 'center',
                render: 'switch',
                replaceValue: {},
            },
            {
                label: t('dingding.config.status'),
                prop: 'status',
                align: 'center',
                render: 'tag',
                replaceValue: {
                    0: t('dingding.config.status 0'),
                    1: t('dingding.config.status 1'),
                },
            },
            {
                label: t('dingding.config.createtime'),
                prop: 'createtime',
                align: 'center',
                render: 'datetime',
                sortable: 'custom',
                operator: 'RANGE',
                width: 160,
            },
            {
                label: t('dingding.config.updatetime'),
                prop: 'updatetime',
                align: 'center',
                render: 'datetime',
                sortable: 'custom',
                operator: false,
                show: false,
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
        dblClickNotEditColumn: [undefined, 'default_switch'],
        defaultOrder: { prop: 'id', order: 'desc' },
    },
    {
        defaultItems: { security: 'sign', status: '1' },
    },
    {},
    {
        onTableAction: ({ event, data }: { event: string; data: anyObj }) => {
            if (event === 'field-change' && data.field.label === '默认项') {
                // 刷新页面，在更改默认项后
                baTable.getIndex()
            }
        },
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
    name: 'dingding/config',
})
</script>

<style scoped lang="scss"></style>
