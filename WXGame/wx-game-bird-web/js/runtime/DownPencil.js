// 下铅笔类
import {BasePencil} from "./BasePencil.js";
import {Sprite} from "../base/Sprite.js";

export class DownPencil extends BasePencil{
    constructor() {
        const image = Sprite.getImage('pencilDown');
        super(image);
    }

    draw() {
        // 间隙定为屏幕的五分之一
        let gap = window.innerHeight / 5;
        this.y = this.top + gap;
        super.draw();
    }
}