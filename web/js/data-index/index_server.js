var layout = localStorage.getItem("index_server_layout");
if(layout !== 'list' && layout !== 'grid'){
    localStorage.setItem("index_server_layout", "grid");
}
var layout = localStorage.getItem("index_server_layout");

if(layout === 'list'){
    $('.layout-select .layout-select__list').addClass('active');
    $('.index .table').css('display', 'block');
    getIndex('list');
}else if(layout === 'grid'){
    $('.layout-select .layout-select__grid').addClass('active');
    $('.index .grid').css('display', 'grid');
    getIndex('grid');
}

$('.layout-select__list').click(function(event){
	event.preventDefault();
    localStorage.setItem("index_server_layout", "list");
    $('.layout-select button').removeClass('active');
    $('.layout-select .layout-select__list').addClass('active');
    $('.index .grid').css('display', 'none');
    $('.index .table').css('display', 'block');
    getIndex('list');
});
$('.layout-select__grid').click(function(event){
	event.preventDefault();
    localStorage.setItem("index_server_layout", "grid");
    $('.layout-select button').removeClass('active');
    $('.layout-select .layout-select__grid').addClass('active');
    $('.index .grid').css('display', 'grid');
    $('.index .table').css('display', 'none');
    getIndex('grid');
});

function getIndex(layout){
    $('.index .table .body').html(`
    <div class="row-skeleton">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <div class="row-skeleton">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <div class="row-skeleton">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <div class="row-skeleton">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    `);
    $('.index .grid').html(`
    <div class="item-skeleton">
        <span class="heading"></span>
        <div class="data">
            <div class="attribute">
                <span></span><span></span>
            </div>
            <div class="attribute">
                <span></span><span></span>
            </div>
            <div class="attribute">
                <span></span><span></span>
            </div>
        </div>
    </div>
    <div class="item-skeleton">
        <span class="heading"></span>
        <div class="data">
            <div class="attribute">
                <span></span><span></span>
            </div>
            <div class="attribute">
                <span></span><span></span>
            </div>
            <div class="attribute">
                <span></span><span></span>
            </div>
        </div>
    </div>
    <div class="item-skeleton">
        <span class="heading"></span>
        <div class="data">
            <div class="attribute">
                <span></span><span></span>
            </div>
            <div class="attribute">
                <span></span><span></span>
            </div>
            <div class="attribute">
                <span></span><span></span>
            </div>
        </div>
    </div>
    <div class="item-skeleton">
        <span class="heading"></span>
        <div class="data">
            <div class="attribute">
                <span></span><span></span>
            </div>
            <div class="attribute">
                <span></span><span></span>
            </div>
            <div class="attribute">
                <span></span><span></span>
            </div>
        </div>
    </div>
    `);
    $.getJSON('/api/server-list.json',function(data){
        //console.log(data);
        if(data.length > 0){
            if(layout === 'grid'){
                $('.index .grid').html('');
                $.each(data,function(key, value){
                    $('.index .grid').append(`
                        <a class="item" href="/servers/`+value['server_slug']+`">
                            <span class="heading">`+value['server_name']+`</span>
                            <div class="data">
                                <div class="attribute">
                                    <span>IP Address</span>
                                    <span>`+value['ip_address']+`</span>
                                </div>
                                <div class="attribute">
                                    <span>Provider</span>
                                    <span>`+value['provider']+`</span>
                                </div>
                                <div class="attribute">
                                    <span>Sites</span>
                                    <span>`+value['site_count']+`</span>
                                </div>
                            </div>
                        </a>
                    `);
                });
            }else if(layout === 'list'){
                $('.index .table .body').html('');
                $.each(data,function(key, value){
                    $('.index .table .body').append(`
                    <a class="row" href="/servers/`+value['server_slug']+`">
                        <span>`+value['server_name']+`</span>
                        <span>`+value['ip_address']+`</span>
                        <span>`+value['provider']+`</span>
                        <span>`+value['site_count']+`</span>
                    </a>
                    `);
                });
            }
        }else{
            $('.index').html(`
            <div class="index-empty">
                <span>Nothing to see here...</span>
            </div>
            `);
        }
        
	});
}