<template>
  <div class="col-md-3">
      <div class="thumbnail_img">
          <img v-bind:src="'/images/product/'+product.thubnail" v-bind:alt="product.title"/>
      </div>
      <div class="title-product">
          <a :href="'/'+product.categorySlug+'/'+product.slug+'.html'">{{product.title}}</a>
      </div>
  </div>
</template>

<script>
  export default {
      props: ['product']
  }
</script>