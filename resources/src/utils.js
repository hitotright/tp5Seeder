/**
 * Created by hitotright on 2019/4/12.
 */

let utils = {
    //去重
    unique1: function (arr) {
        let hash = [];
        for (let i = 0; i < arr.length; i++) {
            if (hash.indexOf(arr[i]) === -1) {
                hash.push(arr[i]);
            }
        }
        return hash;
    },
    //多维转单维
    mult_arr_splice: function (arr,key_arr) {
        if (typeof(key_arr)!=='string'){
            for (let i = 0; i < arr.length; i++) {
                for(let j in arr[i]){
                    if(key_arr.indexOf(j) === -1) {
                       delete arr[i][j]
                    }
                }
            }
        }else{
            let tmp = []
            for (let i = 0; i < arr.length; i++) {
                for(let j in arr[i]){
                    if(key_arr.indexOf(j) !== -1) {
                        tmp.push(arr[i][j])
                    }
                }
            }
            arr = tmp;
        }
        return arr;
    },
    //并集
    union: function (a,b) {
        return a.concat(b.filter(function (v) {return a.indexOf(v) === -1}))
    },
    // 交集
    intersection: function (a,b) {
      return  a.filter(function (v) {return b.indexOf(v) > -1})
    },
    // 差集
    diff: function (a,b) {
       return a.filter(function(v){ return b.indexOf(v) === -1 })
    },
    //重置搜索
    onClear(search,onSubmit){
        for (let key in search) {
            search[key] = "";
        }
        onSubmit()
    },
    //快捷日期
    pickerOptions: {
        shortcuts: [{
            text: '最近一周',
            onClick(picker) {
                const end = new Date();
                const start = new Date();
                start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
                picker.$emit('pick', [start, end]);
            }
        }, {
            text: '最近一个月',
            onClick(picker) {
                const end = new Date();
                const start = new Date();
                start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
                picker.$emit('pick', [start, end]);
            }
        }, {
            text: '最近三个月',
            onClick(picker) {
                const end = new Date();
                const start = new Date();
                start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
                picker.$emit('pick', [start, end]);
            }
        }]
    }
}

export default utils;

