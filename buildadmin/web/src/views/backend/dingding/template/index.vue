<template>
    <div class="default-main ba-table-box">
        <el-alert class="ba-table-alert" v-if="baTable.table.remark" :title="baTable.table.remark" type="info" show-icon />

        <!-- 表格顶部菜单 -->
        <TableHeader
            :buttons="['refresh', 'add', 'edit', 'delete', 'comSearch', 'quickSearch', 'columnDisplay']"
            :quick-search-placeholder="
                t('quick Search Placeholder', {
                    fields: t('dingding.template.quick Search Fields'),
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
import { ElMessageBox } from 'element-plus'

const { t } = useI18n()
const tableRef = ref()
const optButtons = defaultOptButtons(['edit', 'delete'])
optButtons.push({
    render: 'tipButton',
    name: 'test_send',
    title: 'dingding.template.test_send',
    text: '',
    type: 'success',
    icon: 'el-icon-MagicStick',
    class: 'table-row-test',
    disabledTip: false,
    click: (row: TableRow, filed: TableColumn) => {
        ElMessageBox.confirm(t('dingding.template.test send?'), t('dingding.template.test_send'), {
            confirmButtonText: t('dingding.template.send'),
            cancelButtonText: t('Cancel'),
            beforeClose: (action, instance, done) => {
                if (action === 'confirm') {
                    instance.confirmButtonLoading = true
                    instance.confirmButtonText = t('dingding.template.Sending')
                    testSend(row['flag'])
                        .then(() => {
                            done()
                        })
                        .catch(() => {
                            done()
                        })
                } else {
                    done()
                }
            },
        })
    },
})

const baTable = new baTableClass(
    new baTableApi('/admin/dingding.template/'),
    {
        pk: 'id',
        column: [
            { type: 'selection', align: 'center', operator: false },
            {
                label: t('dingding.template.id'),
                prop: 'id',
                align: 'center',
                width: 70,
                sortable: 'custom',
                operator: 'RANGE',
            },
            { label: t('dingding.template.name'), prop: 'name', align: 'center' },
            { label: t('dingding.template.flag'), prop: 'flag', align: 'center' },
            {
                label: t('dingding.template.type'),
                prop: 'type',
                align: 'center',
                render: 'tag',
                replaceValue: {
                    text: t('dingding.template.type text'),
                    markdown: t('dingding.template.type markdown'),
                    actionCardSingle: t('dingding.template.type actionCardSingle'),
                    actionCard: t('dingding.template.type actionCard'),
                    feedCard: t('dingding.template.type feedCard'),
                    link: t('dingding.template.type link'),
                },
            },
            {
                label: t('dingding.template.content'),
                prop: 'content',
                align: 'center',
                'show-overflow-tooltip': true,
            },
            {
                label: t('dingding.template.variables'),
                prop: 'variable_text',
                align: 'center',
                operator: false,
                render: 'tags',
            },
            {
                label: t('dingding.template.createtime'),
                prop: 'createtime',
                align: 'center',
                render: 'datetime',
                sortable: 'custom',
                operator: 'RANGE',
                width: 160,
            },
            {
                label: t('dingding.template.updatetime'),
                prop: 'updatetime',
                align: 'center',
                render: 'datetime',
                sortable: 'custom',
                operator: 'RANGE',
                width: 160,
            },
            {
                label: t('operate'),
                align: 'center',
                width: 140,
                render: 'buttons',
                buttons: optButtons,
                operator: false,
            },
        ],
        dblClickNotEditColumn: [undefined],
        defaultOrder: { prop: 'id', order: 'desc' },
    },
    {
        defaultItems: { type: 'text' },
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
import { testSend } from '/@/api/backend/dingding/template'
export default defineComponent({
    name: 'dingding/template',
})
</script>

<style scoped lang="scss"></style>
