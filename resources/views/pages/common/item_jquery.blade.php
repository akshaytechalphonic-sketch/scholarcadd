<script>    
    function Find_item(modal_id){
        loadModalData();
        openNewModal(modal_id);
    }
    
    
        function loadModalData() {
            $.ajax({
                url: '{{route('get-seachable-itemData')}}',  
                type: 'GET',
                success: function(data) { 
                    $('#ItemCategory').empty().append('<option value="">{{ __('common.chooseoption') }}</option>');
                    $.each(data.categories, function(key, value) {
                        $('#ItemCategory').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
 
                    $('#ItemSubCategory').empty().append('<option value="">{{ __('common.chooseoption') }}</option>');
                    $.each(data.subcategories, function(key, value) {
                        $('#ItemSubCategory').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
 
                    $('#ItemGroup').empty().append('<option value="">{{ __('common.chooseoption') }}</option>');
                    $.each(data.itemGroups, function(key, value) {
                        $('#ItemGroup').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                },
                error: function() {
                    console.error('Error loading modal data.');
                }
            });
        } 
        $('#ItemCategory').on('change', function() {
            var categoryId = $(this).val();
            if (categoryId) {
                $.ajax({
                    url: '{{route('get-categories-subcategory')}}',  
                    type: 'GET',
                    data: { category_id: categoryId },
                    success: function(data) {
                        $('#ItemSubCategory').empty().append('<option value="">{{ __('common.chooseoption') }}</option>');
                        $.each(data, function(key, value) {
                            $('#ItemSubCategory').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                        });
                    },
                    error: function() { 
                    }
                });
            } else {
                $('#ItemSubCategory').empty().append('<option value="">{{ __('common.chooseoption') }}</option>');
            }
        }); 
        $('#ItemSearchForm').on('submit', function(e) {
            e.preventDefault();  
            fetchFilteredItems();
        }); 
        function fetchFilteredItems() {
            var formData = $('#ItemSearchForm').serialize();  

            $.ajax({
                url: '{{route('get-filterdItem-listData')}}', // Replace with your route
                type: 'GET',
                data: formData,
                success: function(data) {
                    $('#FilteredItemListData').empty(); // Clear existing rows
                    $.each(data, function(index, item) {
                        $('#FilteredItemListData').append('<tr> <td class="px-4 py-1"> <input type="checkbox" class="item-checkbox" value="' + item.id + '" name="" id=""> </td> <td class="px-4 py-1">' + item.item_no + '</td> <td class="px-4 py-1">' + item.name + '</td> <td class="px-4 py-1">' + item.description + '</td> </tr>');                                                                
                    });
                },
                error: function() {
                    console.error('Error fetching filtered items.');
                }
            });
        }
        $('#select-all').on('change', function() { 
            $('.item-checkbox').prop('checked', $(this).prop('checked'));
        });
 
        $('.item-checkbox').on('change', function() {
            if ($('.item-checkbox:checked').length === $('.item-checkbox').length) {
                $('#select-all').prop('checked', true);
            } else {
                $('#select-all').prop('checked', false);
            }
        });
        
        
    </script>