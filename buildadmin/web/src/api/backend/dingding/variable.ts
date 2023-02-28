import createAxios from '/@/utils/axios'

export function getVar(id: number) {
    return createAxios({
        url: '/admin/dingding.Variable/getVar',
        method: 'get',
        params: {
            id: id,
        },
    })
}
