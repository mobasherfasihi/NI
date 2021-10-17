<template>
    <div class="container">
        <div class="text-center d-flex flex-row align-items-center" style="margin: 20px 0px 20px 0px;">
            <a href="https://shouts.dev/" target="_blank" class="header-logo"></a><br>
            <nav class="navbar navbar-expand-lg navbar-light bg-light pl-4">
                <div class="collapse navbar-collapse">
                    <!-- for logged-in user-->
                    <div class="navbar-nav" v-if="isLoggedIn">
                        <router-link to="/dashboard" class="nav-item nav-link">Dashboard</router-link>
                        <router-link to="/users" class="nav-item nav-link">Users</router-link>
                        <router-link to="/products" class="nav-item nav-link">Products</router-link>
                        <a class="nav-item nav-link" style="cursor: pointer;" @click="logout">Logout</a>
                    </div>
                    <!-- for non-logged user-->
                    <div class="navbar-nav" v-else>
                        <router-link to="/" class="nav-item nav-link">Home</router-link>
                        <router-link to="/login" class="nav-item nav-link">login</router-link>
                        <router-link to="/register" class="nav-item nav-link">Register
                        </router-link>
                    </div>
                </div>
            </nav>
        </div>
        <router-view/>
    </div>
</template>

<script>
export default {
    name: "App",
    data() {
        return {
            isLoggedIn: false,
        }
    },
    created() {
        if (window.Laravel.isLoggedin) {
            this.isLoggedIn = true
        }
    },
    methods: {
        logout(e) {
            console.log('ss')
            e.preventDefault()
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('/api/logout')
                    .then(response => {
                        if (response.data.success) {
                            window.location.href = "/"
                        } else {
                            console.log(response)
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        }
    },
}
</script>
<style lang="scss" scoped>
    .header-logo {
        display: block;
        background: url(data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20196%2030%22%20width%3D%22196%22%20height%3D%2230%22%3E%3Cstyle%3E.st0%7Bfill%3A%23111%7D.st1%7Bfill%3A%235d86a0%7D%3C%2Fstyle%3E%3Cg%20id%3D%22Layer_1%22%3E%3Cpath%20class%3D%22st0%22%20d%3D%22M50.2%2018.8V9.2h2.4l1.6%204.2h.1V9.2h2.1v9.6h-2.3l-1.7-5v5h-2.2M65.5%2018.8h-2.4l-.2-1.2h-1.5l-.2%201.2h-2.4l1.8-9.7h3l1.9%209.7m-4-3.1h1.2l-.6-4-.6%204zM71.2%2011.2v7.6h-2.5v-7.6h-1.9v-2h6.3v2h-1.9M75.5%209.2H78v9.7h-2.5zM84.9%209.2h2.3l-1.9%209.6h-2.5l-2.3-9.6h2.4l1%205.5h.1l.9-5.5M89.6%2018.8V9.2H95v2h-2.9v1.6h2.4v2.1h-2.4v1.9h3v2h-5.5M102%209.2h2.5v9.7H102zM107.7%2018.8V9.2h2.4l1.6%204.2h.1V9.2h2.1v9.6h-2.3l-1.7-5v5h-2.2M120.8%2012.2c0-1.1-.4-1.3-.9-1.3s-.7.3-.7.6c0%201.2%204%202.1%204%204.8%200%201.9-1.3%202.7-3.4%202.7-2.6%200-3.1-1.7-3.1-3.4h2.3v.4c0%20.6.4%201.1%201%201.1.5%200%20.9-.3.9-.8%200-1.3-4-2.3-4-4.9%200-1.6%201.4-2.5%203.1-2.5%201.9%200%203.1%201.1%203.1%202.7v.4h-2.3M129.7%2011.2v7.6h-2.5v-7.6h-1.9v-2h6.3v2h-1.9M134.2%2018.8V9.2h3.1c2.1%200%203.1.6%203.1%202.7%200%201.5-.7%202-1.2%202.2.9.1%201.2%201.5%201.2%202.7%200%201%20.1%201.6.3%202h-2.4c-.2-.8-.3-1.3-.3-2%200-1.6-.1-1.9-1-1.9h-.4v3.9h-2.4m2.4-5.6h.4c.7%200%20.9-.4.9-1.2%200-.8-.2-1.2-.9-1.2h-.4v2.4zM149.5%209.2v7.3c0%201.6-1.1%202.5-3.1%202.5s-3.1-.9-3.1-2.5V9.2h2.5v7c0%20.5.1.8.6.8s.6-.3.6-.8v-7h2.5M152.5%2018.8V9.2h2.9l1.1%204.6%201-4.6h3v9.6h-2.2v-5.7l-1.1%205.7h-1.4l-1.1-5.7h-.1v5.7h-2.1M163.4%2018.8V9.2h5.4v2h-2.9v1.6h2.4v2.1h-2.4v1.9h3.1v2h-5.6M171.8%2018.8V9.2h2.4l1.6%204.2V9.2h2.2v9.6h-2.4l-1.7-5v5h-2.1M184.9%2011.2v7.6h-2.5v-7.6h-1.9v-2h6.3v2h-1.9M192.9%2012.2c0-1.1-.4-1.3-.9-1.3s-.7.3-.7.6c0%201.2%204%202.1%204%204.8%200%201.9-1.3%202.7-3.4%202.7-2.6%200-3.1-1.7-3.1-3.4h2.3v.4c0%20.6.4%201.1%201%201.1.5%200%20.9-.3.9-.8%200-1.3-4-2.3-4-4.9%200-1.6%201.4-2.5%203.1-2.5%201.9%200%203.1%201.1%203.1%202.7v.4h-2.3%22%2F%3E%3C%2Fg%3E%3Cg%20id%3D%22Layer_4%22%3E%3Cpath%20class%3D%22st1%22%20d%3D%22M40%203H21v24h19V3zm-9%2019h-3V8h3v14z%22%2F%3E%3Cpath%20class%3D%22st0%22%20d%3D%22M2.6%203C8.7%204.5%2014.8%205.3%2021%205.3c6.1%200%2012.3-.8%2018.4-2.3l-1.6-.9c.5%201.2.8%203.2%201.1%205.5.2%202.3.4%204.8.4%207.4%200%202.6-.1%205.2-.4%207.4-.2%202.3-.6%204.2-1.1%205.5l1.6-.9c-6.1-1.5-12.3-2.3-18.4-2.3h-.1c-6.1%200-12.3.8-18.4%202.3l1.7.9c-.4-1.2-.8-3.2-1.1-5.5-.2-2.3-.4-4.8-.4-7.4%200-2.6.1-5.2.4-7.4.2-2.3.6-4.2%201.1-5.5L2.6%203l.3-1.4-1.3-.4C1%202.8.7%204.9.4%207.3.1%209.7%200%2012.3%200%2015s.1%205.3.4%207.7c.3%202.4.6%204.5%201.2%206.1L2%2030l1.2-.3c5.9-1.5%2011.8-2.2%2017.7-2.2%205.9%200%2011.8.7%2017.7%202.2l1.2.3.4-1.2c.6-1.6.9-3.7%201.2-6.1.3-2.4.4-5.1.4-7.7%200-2.7-.1-5.3-.4-7.7-.3-2.4-.6-4.5-1.2-6.1L39.9%200l-1.2.3C32.8%201.8%2026.9%202.5%2021%202.5h-.1C15%202.5%209.1%201.8%203.3.3L2%200l-.4%201.2%201.3.5%22%2F%3E%3Cpath%20class%3D%22st0%22%20d%3D%22M11%2022v-7l2%207h3V8h-3v6l-2-6H8v14z%22%2F%3E%3C%2Fg%3E%3C%2Fsvg%3E) no-repeat;
        background-size: 196px 30px;
        width: 196px;
        height: 30px;
    }
</style>
