<template>
    <div>
        <p class="global">{{ greeting }} World!</p>
        <p class="scp">{{ scopedMsg }} !</p>
    </div>
</template>

<script>
    module.exports = {
    	// props: ['options'],
        data() {
            return {
				greeting: 'Bonjour',
				scopedMsg: 'Welcome to the MyVueLib Demo'
            }
        }
    }
</script>

<style>
    p.global {
        color: #41B883;
        font-size: 2em;
        text-align: center;
    }
</style>

<style scoped>
    p.scp {
        color: blue;
        font-size: 3em;
        text-align: center;
    }
</style>
