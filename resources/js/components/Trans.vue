<template>
    <div id="tran">{{ result }}</div>
</template>
<script>
export default {
    name: 'trans',

    props: ['path'],

    data() {
        return {
            result: '',
        };
    },
    methods: {
        getTranslation() {
            axios
                .get(`/api/lang/${this.path}`)
                .then((response) => {
                    this.result = response.data.results;
                })
                .catch((error) => {
                    console.error(`Something went wrong fetching ${this.path}`);
                });
        },
    },

    created() {
        this.getTranslation();
    },
};
</script>
