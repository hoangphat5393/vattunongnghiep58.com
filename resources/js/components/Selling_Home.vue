<template>
    <div id="Selling_Home" class="clear">
        <div v-if="loading">
            <p class="tai">Đang tải dữ liệu..</p><div id="loader"></div>
        </div>
        <div class="product_item_selling_group bg_white" v-if="products.length">
            <div class="container_selling_sale clear" v-html="products"></div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Selling_Home",
        props: [
            'quote',
        ],
        data() {
            return {
                products:[],
                loading: false
            }
        },
        mounted(){
            this.loading = true;
            //console.log(this.quote);
            let id_category=this.quote;
            let url_requet='./api/category-products-html-selling/'+id_category;
            axios.get(url_requet)
                .then((response) => {
                    this.loading = false;
                    let productData  = response.data;
                    if(productData.length > 0) {
                        this.products = productData;
                        //console.log(this.products);
                    }
                }, (error) => {
                    this.loading = false;
                });
        },
    }
</script>

<style scoped>

</style>