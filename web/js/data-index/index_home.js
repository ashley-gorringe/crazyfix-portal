$('.layout-select .layout-select__list').addClass('active');
$('.index .table').css('display', 'block');
getIndex('list');

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
    $.getJSON('/api/site-list.json?context=home',function(data){
        //console.log(data);
        if(data.length > 0){
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
        }else{
            $('.index').html(`
            <div class="index-empty">
                <span>Nothing to see here...</span>
            </div>
            `);
        }
        
	});
}