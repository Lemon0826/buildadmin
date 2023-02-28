/**
 * 添加水印
 * @param val     水印
 * @param width   画布宽
 * @param height  画布高
 * @param rotate  水印倾斜角度
 * @param size    水印字体大小
 * @param color   水印颜色
 */
export const watermark = (val: any, width?: number, height?: number, rotate?: number, size?: number, color?: string) => {
    let id = setWatermark(val, width, height, rotate, size, color)
    setInterval(() => {
        if (document.getElementById(id) === null) {
            id = setWatermark(val, width, height, rotate, size, color)
        }
    }, 500)
    window.onresize = () => {
        setWatermark(val, width, height, rotate, size, color)
    }
}

const setWatermark = (val: any, width?: number, height?: number, rotate?: number, size?: number, color?: string) => {
    const id = '3.1415926'
    if (document.getElementById(id) !== null) {
        document.body.removeChild(document.getElementById(id)!)
    }

    //创建一个画布
    const can = document.createElement('canvas')
    //设置画布的长宽
    can.width = width ? width : 550
    can.height = height ? height : 120

    const cans = can.getContext('2d')!
    //旋转角度
    const r = rotate ? rotate : -15
    cans.rotate((r * Math.PI) / 180)
    //字体大小
    const s = size ? size : 18
    cans.font = s + 'px Vedana'
    //设置填充绘画的颜色、渐变或者模式
    cans.fillStyle = color ? color : 'rgba(200, 200, 200, 0.40)'
    //设置文本内容的当前对齐方式
    cans.textAlign = 'left'
    //设置在绘制文本时使用的当前文本基线
    // cans.textBaseline = "Middle";
    //在画布上绘制填色的文本（输出的文本，开始绘制文本的X坐标位置，开始绘制文本的Y坐标位置）
    cans.fillText(val, can.width / 8, can.height / 2)

    const div = document.createElement('div')
    div.id = id
    div.style.pointerEvents = 'none'
    div.style.top = '30px'
    div.style.left = '0px'
    div.style.position = 'fixed'
    div.style.zIndex = '999'
    div.style.width = document.documentElement.clientWidth + 'px'
    div.style.height = document.documentElement.clientHeight + 'px'
    div.style.background = 'url(' + can.toDataURL('image/png') + ') left top repeat'
    document.body.appendChild(div)
    return id
}
