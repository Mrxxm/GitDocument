// 资源文件加载器类
import {Resources} from "./Resources.js";

export class ResourceLoader {
    constructor() {
        this.map = new Map(Resources);
        for (let [key, value] of this.map) {
            const image = wx.createImage();
            image.src = value;
            this.map.set(key, image); // map的键值是唯一的，这里重新赋值
        }
    }

    // 保证图片资源加载完成
    onLoaded(callback) {
        let loadCount = 0;
        for (let value of this.map.values()) {
            // onload方法生命周期函数，监听页面加载
            value.onload = () => {
                loadCount++;
                if (loadCount >= this.map.size) {
                    callback(this.map);
                }
            }
        }
    }

    static create() {
        return new ResourceLoader();
    }
}