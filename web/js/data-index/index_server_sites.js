var server_slug = $('.index').data('server');

var sort = localStorage.getItem("index_site_sort");
if(sort !== 'updated-desc' && sort !== 'updated-asc' && sort !== 'name-asc' && sort !== 'name-desc' && sort !== 'created-desc' && sort !== 'created-asc'){
    localStorage.setItem("index_site_sort", "updated-desc");
}
var sort = localStorage.getItem("index_site_sort");
$('select[name="sort"]').val(sort);
$('select[name="sort"]').change(function(event){
    sort = $('select[name="sort"]').val();
    localStorage.setItem("index_site_sort", sort);
    event.preventDefault();
    getIndex(layout);
});

var layout = localStorage.getItem("index_site_layout");
if(layout !== 'list' && layout !== 'grid'){
    localStorage.setItem("index_site_layout", "grid");
}
var layout = localStorage.getItem("index_site_layout");

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
    localStorage.setItem("index_site_layout", "list");
    $('.layout-select button').removeClass('active');
    $('.layout-select .layout-select__list').addClass('active');
    $('.index .grid').css('display', 'none');
    $('.index .table').css('display', 'block');
    getIndex('list');
});
$('.layout-select__grid').click(function(event){
	event.preventDefault();
    localStorage.setItem("index_site_layout", "grid");
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
        <span></span>
    </div>
    <div class="row-skeleton">
        <span></span>
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
        <span></span>
    </div>
    <div class="row-skeleton">
        <span></span>
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
            <div class="attribute">
                <span></span><span></span>
            </div>
        </div>
    </div>
    `);
    $.getJSON('/api/site-list.json?sort='+sort+'&server='+server_slug,function(data){
        //console.log(data);
        if(data.length > 0){
            if(layout === 'grid'){
                $('.index .grid').html('');
                $.each(data,function(key, value){
                    if(value['issue_flag'] == 1 && value['issue_dns'] == 1){
                        var issue_class = ' --issue-2';
                        var issues = `
                        <div class="issues">
                            <i data-feather="flag"></i>
                            <i data-feather="link"></i>
                        </div>
                        `;
                    }else if(value['issue_flag'] == 1 && value['issue_dns'] == 0){
                        var issue_class = ' --issue-1';
                        var issues = `
                        <div class="issues">
                            <i data-feather="flag"></i>
                        </div>
                        `;
                    }else if(value['issue_flag'] == 0 && value['issue_dns'] == 1){
                        var issue_class = ' --issue-1';
                        var issues = `
                        <div class="issues">
                            <i data-feather="link"></i>
                        </div>
                        `;
                    }else{
                        var issue_class = '';
                        var issues = '';
                    }
    
                    $('.index .grid').append(`
                        <a class="item`+issue_class+`" href="/sites/`+value['site_slug']+`">
                            `+issues+`
                            <span class="heading">`+value['site_name']+`</span>
                            <div class="data">
                                <div class="attribute">
                                    <span>Client</span>
                                    <span>`+value['client_name']+`</span>
                                </div>
                                <div class="attribute">
                                    <span>Server</span>
                                    <span>`+value['server_name']+`</span>
                                </div>
                                <div class="attribute">
                                    <span>Date Created</span>
                                    <span>`+value['created_timeago']+`</span>
                                </div>
                                <div class="attribute">
                                    <span>Last Updated</span>
                                    <span>`+value['updated_timeago']+`</span>
                                </div>
                            </div>
                        </a>
                    `);
                });
                feather.replace();
            }else if(layout === 'list'){
                $('.index .table .body').html('');
                $.each(data,function(key, value){
                    if(value['issue_flag'] == 1 && value['issue_dns'] == 1){
                        var issue_class = ' --issue-f-d';
                        var issues = `
                        <div class="issues">
                            <i data-feather="flag"></i>
                            <i data-feather="link"></i>
                        </div>
                        `;
                    }else if(value['issue_flag'] == 1 && value['issue_dns'] == 0){
                        var issue_class = ' --issue-f';
                        var issues = `
                        <div class="issues">
                            <i data-feather="flag"></i>
                        </div>
                        `;
                    }else if(value['issue_flag'] == 0 && value['issue_dns'] == 1){
                        var issue_class = ' --issue-d';
                        var issues = `
                        <div class="issues">
                            <i data-feather="link"></i>
                        </div>
                        `;
                    }else{
                        var issue_class = '';
                        var issues = '';
                    }
    
                    $('.index .table .body').append(`
                    <a class="row`+issue_class+`" href="/sites/`+value['site_slug']+`">
                        `+issues+`
                        <span>`+value['site_name']+`</span>
                        <span>`+value['client_name']+`</span>
                        <span>`+value['server_name']+`</span>
                        <span>`+value['created_timeago']+`</span>
                        <span>`+value['updated_timeago']+`</span>
                    </a>
                    `);
                });
                feather.replace();
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