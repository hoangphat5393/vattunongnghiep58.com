    <div class="text-end">
        <a href="javascript:;" class="add-new-variation" title="">+ Add new avariable</a>
    </div>

    <div class="list-variation">
        @include('admin.product.variation.variation-list', ['product_id' => $product_id ])
    </div>

@push('styles')
<!-- add styles here -->

@endpush

@push('scripts-footer')
<!-- add script here -->
<script>
    jQuery(document).ready(function($) {
        $('.add-new-variation').click(function(event) {
            axios({
              method: 'get',
              url: '{{ route("admin.product.variation.add") }}?product_id={{ $product_id }}'
            }).then(res => {
              if(res.data.view != '')
                $('footer').append(res.data.view);
            }).catch(e => console.log(e));
        });

        //edit-variation
        $(document).on('click', '.edit-variation', function(){
            var id = $(this).attr('data');
            axios({
                  method: 'get',
                  url: '{{ route("admin.product.variation.edit") }}/'+ id + '?product_id={{ $product_id }}'
                }).then(res => {
                  if(res.data.view != '')
                    $('footer').append(res.data.view);
                }).catch(e => console.log(e));
            return false;
        });
        //edit-variation

        //delete-variation
        $(document).on('click', '.delete-variation', function(){
            var id = $(this).attr('data'),
                this_ = $(this);
            axios({
              method: 'post',
              url: '{{ route("admin.product.variation.delete") }}',
              data: {id:id}
            }).then(res => {
              if(res.data.error == 0){
                this_.closest('tr').remove();
              }
            }).catch(e => console.log(e));
        return false;
        });
        //delete-variation
    });
</script>
@endpush
