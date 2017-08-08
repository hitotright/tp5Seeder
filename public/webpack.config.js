/**
 * Created by hitoTright on 2017/6/8.
 */
/* 引入操作路径模块和webpack */
const path = require('path');
const webpack = require('webpack');

module.exports = {
    /* 输入文件 */
    entry: './src/main.js',
    output: {
        /* 输出目录，没有则新建 */
        path: path.resolve(__dirname, './build'),
        /* 静态目录，可以直接从这里取文件 */
        publicPath: '/build/',
        /* 文件名 */
        filename: 'main.js'
    },
    module: {
        rules: [
            /* 用来解析vue后缀的文件 */
            {
                test: /\.vue$/,
                loader: 'vue-loader'
            },
            /* 用babel来解析js文件并把es6的语法转换成浏览器认识的语法 */
            {
                test: /\.js$/,
                loader: 'babel-loader',
                /* 排除模块安装目录的文件 */
                exclude: /node_modules/
            }
        ]
    }
}