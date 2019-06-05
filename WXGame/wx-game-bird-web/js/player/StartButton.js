// 开始按钮类
import {Sprite} from "../base/Sprite.js";

export class StartButton extends Sprite{
    constructor() {
        const image = Sprite.getImage('startButton');
        super(image,
            0, 0,
            image.width, image.height,
            window.innerWidth / 2.5,  window.innerHeight / 3,
            image.width, image.height);
    }
}