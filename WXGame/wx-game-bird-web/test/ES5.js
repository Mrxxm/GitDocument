(function () {
    'use strict';

    var Animal = function (name, age) {
        this.name = name;
        this.age  = age;
        this.talk = function () {
            console.log("talk方法");
        }
    };
    Animal.prototype.talking = function () {
        console.log("talking方法");
    }
    Animal.prototype.say = function () {
        console.log(this.name + ' ' + this.age);
    };

    var Cat = function (name, age) {
        Animal.apply(this, arguments);
    };
    Cat.prototype = Object.create(Animal.prototype);
    Cat.prototype.say = function () {
        console.log('子类' + this.name + this.age);
        // 调用父类方式
        Animal.prototype.say.apply({name: '父类', age: 10});
    }

    var cat1 = new Cat('子猫', 5);
    cat1.talk();      // 验证apply()
    cat1.talking();  // 验证浅拷贝
    cat1.say();
})();