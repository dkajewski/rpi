<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <router-link tag="a" class="navbar-brand" to="/">RPI</router-link>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <router-link tag="a" class="navbar-brand" to="/">Home</router-link>
                </li>
                <li class="nav-item">
                    <router-link tag="a" class="navbar-brand" to="/admin">Admin</router-link>
                </li>
            </ul>
        </div>
    </nav>
</template>
<script>
    export default {
        data() {
            return {

            }
        },
    }
</script>