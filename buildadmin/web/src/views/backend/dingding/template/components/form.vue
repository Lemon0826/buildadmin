<template>
    <div>
        <el-form ref="formRef" :model="value" v-bind="$attrs">
            <template v-if="props.type === 'feedCard'">
                <el-form-item v-for="(link, index) in value['links']" :key="index" :label="'链接' + index">
                    <el-row :gutter="8">
                        <el-col :span="9">
                            <el-input v-model="link.title" placeholder="请输入链接标题" />
                        </el-col>
                        <el-col :span="15">
                            <el-input v-model="link.messageURL" placeholder="请输入跳转地址" />
                        </el-col>
                        <el-col :span="22">
                            <el-input v-model="link.picURL" placeholder="请输入图片URL" />
                        </el-col>
                        <el-col :span="2">
                            <el-button circle @click="delLink(index)">
                                <Icon name="el-icon-Delete" class="delete-btn" />
                            </el-button>
                        </el-col>
                    </el-row>
                </el-form-item>
                <el-form-item label="新链接">
                    <el-row :gutter="8">
                        <el-col :span="9">
                            <el-input v-model="newLink.title" placeholder="请输入链接标题" />
                        </el-col>
                        <el-col :span="15">
                            <el-input v-model="newLink.messageURL" placeholder="请输入跳转地址" />
                        </el-col>
                        <el-col :span="22">
                            <el-input v-model="newLink.picURL" placeholder="请输入图片URL" />
                        </el-col>
                        <el-col :span="2">
                            <el-button circle @click="addLink()">
                                <Icon name="el-icon-Plus" />
                            </el-button>
                        </el-col>
                    </el-row>
                </el-form-item>
            </template>
            <template v-else>
                <div v-for="item in props.config" :key="item.prop">
                    <el-form-item v-if="isMarkdown(item.prop)" v-bind="item">
                        <el-input v-model="value[item.prop]" :autosize="{ minRows: 3 }" type="textarea" placeholder="请输入内容" />
                    </el-form-item>
                    <template v-else-if="item.prop === 'btns'">
                        <el-form-item v-for="(btn, index) in value['btns']" :key="index" :label="item.label + index">
                            <el-row :gutter="8">
                                <el-col :span="7">
                                    <el-input v-model="btn.title" placeholder="请输入按钮名称" />
                                </el-col>
                                <el-col :span="15">
                                    <el-input v-model="btn.actionURL" placeholder="请输入跳转地址" />
                                </el-col>
                                <el-col :span="2">
                                    <el-button circle @click="delBtn(index)">
                                        <Icon name="el-icon-Delete" class="delete-btn" />
                                    </el-button>
                                </el-col>
                            </el-row>
                        </el-form-item>
                        <el-form-item label="新按钮">
                            <el-row :gutter="8">
                                <el-col :span="7">
                                    <el-input v-model="newBtn.title" placeholder="请输入按钮名称" />
                                </el-col>
                                <el-col :span="15">
                                    <el-input v-model="newBtn.actionURL" placeholder="请输入跳转地址" />
                                </el-col>
                                <el-col :span="2">
                                    <el-button circle @click="addBtn()">
                                        <Icon name="el-icon-Plus" />
                                    </el-button>
                                </el-col>
                            </el-row>
                        </el-form-item>
                    </template>
                    <FormItem v-else v-model="value[item.prop]" v-bind="item" placeholder="请输入" />
                </div>
            </template>
        </el-form>
    </div>
</template>

<script setup lang="ts">
import { reactive, ref, watch } from 'vue'
import FormItem from '/@/components/formItem/index.vue'
import { ElForm, ElMessage } from 'element-plus'

export interface SubForm {
    formRef: typeof ElForm
}

interface Props {
    modelValue: any
    type: string
    config: any[]
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: '',
    type: 'text',
    config: () => [],
})

const formRef = ref<InstanceType<typeof ElForm>>()
defineExpose({
    formRef,
})

const emit = defineEmits(['update:modelValue'])

const initForm: Record<string, Object> = {
    markdown: {
        title: '',
        text: '',
    },
    actionCardSingle: {
        title: '',
        text: '',
        singleTitle: '',
        singleURL: '',
    },
    actionCard: {
        title: '',
        text: '',
        btnOrientation: '0',
        btns: [],
    },
    feedCard: {
        links: [],
    },
}

const value = ref(props.modelValue ? JSON.parse(props.modelValue) : initForm[props.type])

watch(
    () => value,
    () => {
        emit('update:modelValue', JSON.stringify(value.value))
    },
    {
        deep: true,
    }
)

const newBtn = reactive({
    title: '',
    actionURL: '',
})

const newLink = reactive({
    title: '',
    messageURL: '',
    picURL: '',
})

const isMarkdown = (prop: string) => {
    const markdownArr = ['markdown', 'actionCard', 'actionCardSingle']
    return markdownArr.find((e) => e === props.type) && prop === 'text'
}

const addBtn = () => {
    if (newBtn.title === '' || newBtn.actionURL === '') {
        ElMessage.error('新按钮的标题和链接不能为空！')
        return
    }

    let btns = (value.value as Record<string, any>)['btns'] as any[]
    const { ...newVal } = newBtn
    // 添加
    btns.push(newVal)
    // 重置
    newBtn.title = ''
    newBtn.actionURL = ''
}

const delBtn = (index: number) => {
    let btns = (value.value as Record<string, any>)['btns'] as any[]
    // 移除
    btns.splice(index, 1)
}

const addLink = () => {
    if (newLink.title === '' || newLink.messageURL === '' || newLink.picURL === '') {
        ElMessage.error('新链接的标题和链接不能为空！')
        return
    }

    let links = (value.value as Record<string, any>)['links'] as any[]
    const { ...newVal } = newLink
    // 添加
    links.push(newVal)
    // 重置
    newLink.title = ''
    newLink.messageURL = ''
    newLink.picURL = ''
}

const delLink = (index: number) => {
    let links = (value.value as Record<string, any>)['links'] as any[]
    // 移除
    links.splice(index, 1)
}
</script>

<style scoped lang="scss">
.delete-btn {
    color: var(--el-color-danger) !important;
}
</style>
