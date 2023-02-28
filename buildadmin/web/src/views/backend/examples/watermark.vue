<template>
    <div class="default-main">
        <el-form class="form-box" label-width="50px" label-position="right" ref="formRef" :model="form">
            <FormItem
                label="水印"
                label-width="50px"
                type="string"
                v-model="form.val"
                prop="str"
                :input-attr="{ clearable: true }"
                placeholder="页面水印"
            />
            <el-row>
                <el-col :span="12">
                    <FormItem label="宽" type="number" v-model="form.width" prop="width" :input-attr="{ clearable: true }" placeholder="画布宽" />
                </el-col>
                <el-col :span="12">
                    <FormItem label="高" type="number" v-model="form.height" prop="height" :input-attr="{ clearable: true }" placeholder="画布高" />
                </el-col>
            </el-row>
            <el-row>
                <el-col :span="12">
                    <FormItem
                        label="角度"
                        type="number"
                        v-model="form.rotate"
                        prop="rotate"
                        :input-attr="{ clearable: true }"
                        placeholder="倾斜角度"
                    />
                </el-col>
                <el-col :span="12">
                    <FormItem
                        label="字体大小"
                        label-width="80px"
                        type="number"
                        v-model="form.size"
                        prop="size"
                        :input-attr="{ clearable: true }"
                        placeholder="字体大小"
                    />
                </el-col>
            </el-row>
            <el-row>
                <el-col :span="12">
                    <FormItem
                        label="颜色"
                        type="string"
                        v-model="form.color"
                        prop="color"
                        :input-attr="{ clearable: true }"
                        placeholder="颜色（rgb）"
                    />
                </el-col>
                <el-col :span="11" :push="1"><el-color-picker v-model="form.color" show-alpha /></el-col>
            </el-row>

            <div class="sub-buttons">
                <el-button type="primary" :loading="form.loading" @click="onSubmit()">提交</el-button>
                <el-button @click="onResetForm(formRef)">重置</el-button>
            </div>
        </el-form>
    </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import FormItem from '/@/components/formItem/index.vue'
import type { FormInstance } from 'element-plus'
import { onResetForm } from '/@/utils/common'
import { watermark } from '/@/utils/watermark'
const formRef = ref<FormInstance>()

const form = reactive({
    loading: false,
    val: 'buildAdmin',
    width: 550,
    height: 120,
    rotate: -15,
    size: 18,
    color: ref('rgba(200, 200, 200, 0.40)'),
})

const onSubmit = () => {
    watermark(form.val, form.width, form.height, form.rotate, form.size, form.color)
}
</script>

<style scoped lang="scss">
.default-main {
    padding: 20px;
    border-radius: var(--el-border-radius-base);
    background-color: var(--ba-bg-color-overlay);
}
.form-box {
    width: 600px;
    margin: 0px auto;
}
.sub-buttons {
    display: flex;
    justify-content: center;
}
</style>
