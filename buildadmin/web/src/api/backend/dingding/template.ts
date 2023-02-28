import createAxios from '/@/utils/axios'

export function testSend(flag: string) {
    return createAxios({
        url: '/admin/dingding.Template/testSend',
        method: 'get',
        params: {
            flag,
        },
    })
}
