<template>
    <div id="Offer_Home" class="clear">

        <div class="product_item_selling_group bg_white">
            <div class="container_selling_sale clear" v-if="items.data">
                <div class="product-item col col-lg-5ths" v-for="(item,index) in items.data">
                    <div class="product_item_list clear">
                        <div class="item-thumb">
                            <a class="effect" :href="'/'+item.categorySlug+'/'+item.slug+'.html'">
                                <img v-bind:src="'/images/product/'+item.thubnail" v-bind:alt="item.thubnail_alt"/>
                                <span class="productlabels_icons clear"></span>
                            </a>
                        </div>
                        <div class="pro-info">
                            <h3 class="product-name"><a :href="'/'+item.categorySlug+'/'+item.slug+'.html'">{{item.title}}</a></h3>

                            <div class="price-box clear">
                                <div class="box_price_item">
                                            <span class="special-price">
                                                <span class="price-label">Special Price</span>
                                                <span class="price" id="product-price-'.$row->id.'">
                                                    {{formatPrice(item.price_promotion)}} đ
                                                </span>
                                            </span>
                                    <span class="old-price">
                                                <span class="price-label">Regular Price:</span>
                                                <span class="price" id="old-price-">
                                                    {{formatPrice(item.price_origin)}} đ
                                                </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="loading">
                <p class="tai">Đang tải dữ liệu..</p><div id="loader"></div>
            </div>
            <div v-if="items.next_page_url" class="load_more_offer">
                <button @click.prevent="paginate(items.next_page_url)" type="button" class="btn btn-primary tbl_load_more"><i class="fa-spinner fa"></i> Tải thêm</button>
            </div>
        </div>

    </div>
</template>
<script>
    export default {
        name: "Offer_Home",
        props: [
            'quote',
        ],
        data() {
            return {
                items: {},
                loading: false
            }
        },
        mounted() {
            this.loading = true;
            this.paginate()
        },
        methods: {
            formatPrice(value) {
                let val = (value/1).toFixed(0).replace(',', '.')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            paginate(url = '') {
                this.loading = true;
                let id_category=this.quote;
                let url_requet='./api/category-products-html-offer/'+id_category;
                axios.get(url ? url : url_requet)
                    .then(response => {
                        this.loading = false;
                        if (! this.items.data) {
                            this.items = response.data;
                        }
                        else {
                            this.items.data.push(...response.data.data);
                            this.items.next_page_url = response.data.next_page_url;
                        }
                    },(error) => {
                        this.loading = false;
                    });
                //console.log(this.items);
            }
        }
    }
</script>

<style scoped>

</style>