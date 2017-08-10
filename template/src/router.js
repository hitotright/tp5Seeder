const routers = [{
    path: '/',
    meta: {
        title: '首页'
    },
    component: (resolve) => require(['./views/index.vue'], resolve),
},
    {
        path: '/home',
        component: (resolve) => require(['./views/home.vue'], resolve),
        meta: {title: 'Home'},
        children:[
            {path:'user',component:(resolve)=>require(['./views/user.vue'],resolve),meta:{title:'User'}}
        ]
    }
];
export default routers;