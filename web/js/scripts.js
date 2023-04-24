feather.replace();

$(".service_date_picker").flatpickr({
	minDate: "today",
	altInput: true,
    altFormat: "F j Y",
    dateFormat: "Y-m-d",
	"disable": [
        function(date) {
            // return true to disable
            return (date.getDay() === 0 || date.getDay() === 6);

        }
    ],
    "locale": {
        "firstDayOfWeek": 1 // start week on Monday
    }
});

$('.sidebar-slim').on('mouseenter', function(event) {
    event.preventDefault();
    $( ".sidebar" ).addClass( "active" );
    event.stopPropagation();
});
$('.sidebar').on('mouseenter', function(event) {
    event.preventDefault();
    $( ".sidebar" ).addClass( "active" );
    event.stopPropagation();
});
$('main').on('mouseenter', function(event) {
    event.preventDefault();
    $( ".sidebar" ).removeClass( "active" );
    event.stopPropagation();
});

let previousScroll = 0;
$(window).on("scroll", function() {
    let currentScroll = $(window).scrollTop();
    if (currentScroll > 75 && currentScroll > previousScroll) {
        $(".top-bar").addClass("--scrolled");
    } else if (currentScroll < 75 && currentScroll < previousScroll) {
        $(".top-bar").removeClass("--scrolled");
    }
    previousScroll = currentScroll;
});

$('.form-toggle').on('click', function(event) {
    event.preventDefault();
	//alert('TEST');
	if($(this).hasClass('active')){
		$(this).removeClass('active');
		$(this).siblings('.form-toggle-input').val('false');
	}else{
		$(this).addClass('active');
		$(this).siblings('.form-toggle-input').val('true');
	}
});

/*
$('.layout-select__list').click(function(event){
	event.preventDefault();
    window.location=window.location.href.split('?')[0] + "?layout=list"
});
$('.layout-select__grid').click(function(event){
	event.preventDefault();
    window.location=window.location.href.split('?')[0] + "?layout=grid"
});
*/

$( document ).on('submit','.process-form',function(event){
	//console.log( $( this ).serialize() );
	event.preventDefault();
	
	var submitButton = $(this).find(':submit');
	var submitButtonHTML = submitButton.html();

    var formElements = $(this);
	
	submitButton.prop('disabled', true);
	submitButton.addClass('--processing');
    formElements.find('.form-control').removeClass( "--invalid" );

    formElements.find('.form-control').prop('readonly', true);
	formElements.find('.form-select').prop('readonly', true);
	
	var formData = new FormData(this);
	console.log(formData);

	formElements.find('.form-control').removeClass( "--invalid" );
	formElements.find('.form-select').removeClass( "--invalid" );
	
	function submitAjax(submitButton,submitButtonHTML,formData){
		$.ajax({
			type: "POST",
			url: '/process.php',
			data: formData,
			cache:false,
			contentType: false,
			processData: false,
			success: function(response){
				console.log(response);
				if(response.status == 'error'){
					submitButton.prop('disabled', false);
					submitButton.removeClass('--processing');
                    formElements.find('.form-control').prop('readonly', false);
	                formElements.find('.form-select').prop('readonly', false);
					
					if(typeof response.errorFields !== "undefined"){
						var errorFields = response.errorFields;
						errorFields.forEach(function(errorFieldsItem) {
							var fieldName = errorFieldsItem['field_name'];
							var fieldElement = '[name="'+fieldName+'"]';
							$(fieldElement).addClass( "--invalid" );
							if(errorFieldsItem['error_message']){
								var fieldErrorMessage = errorFieldsItem['error_message'];
								$(fieldElement).siblings('.form-error').html( fieldErrorMessage );
							}
						});
					}
                    if(typeof response.message !== 'undefined'){
						swal({
                            title: "Hold up!",
                            text: response.message,
                            icon: "error",
                        });
					}
				
				}else if(response.status == 'confirm'){
					console.log(formData);
					
					swal({
						icon: "warning",
						title: "Are you sure?",
						text: response.message,
						buttons: {
							cancel: 'Cancel',
							confirm: 'Confirm',
						},
					}).then((willConfirm) => {
						if (willConfirm) {
							submitAjax(submitButton,submitButtonHTML,formData+'&confirm=true');
						}
					});
					
				}else if(response.status == 'success'){
					if(typeof response.successRedirect !== 'undefined'){
						window.location.href = response.successRedirect;
					}
					if(typeof response.successCallback !== 'undefined'){
						console.log('Callback Function: '+response.successCallback);
						window[response.successCallback](response.successCallbackParams);
					}
					if(typeof response.message !== 'undefined'){
						swal({
							text: response.message,
						});
					}
					submitButton.prop('disabled', false);
					submitButton.removeClass('--processing');
                    formElements.find('.form-control').prop('readonly', false);
	                formElements.find('.form-select').prop('readonly', false);
				}
			}
		});
	}
	submitAjax(submitButton,submitButtonHTML,formData);
	
});

//When any form with the class 'page-form' has any changes, add a class to the form '--changed'. Make sure that this happens as soon as the user has changed the form, not when they lose focus.
$( document ).on('change keyup paste','.page-form',function(event){
	$(this).addClass('--changes');
});


//When the button with the class 'page-form-cancel-button' reset the form
$( document ).on('click','.page-form-cancel-button',function(event){
	event.preventDefault();
	$(this).closest('form').removeClass('--changes');
	$(this).closest('form')[0].reset();
	//Remove class '--invalid' from all form elements
	$(this).closest('form').find('.form-control').removeClass( "--invalid" );
	$(this).closest('form').find('.form-select').removeClass( "--invalid" );

});

$('#sign-out-link').click(function(event){
	event.preventDefault();
	swal({
		title: "Are you sure?",
		text: "You'll need to sign back in before you can access ServerBook again.",
		buttons: {
			cancel: 'Cancel',
			confirm: 'Sign out',
		},
	}).then((willDelete) => {
		if (willDelete) {
			$.ajax({
				type: "POST",
				url: '/process.php',
				data: "action=sign-out",
				dataType: 'json',
				success: function(response){
					console.log(response);
					if(response.status == 'error'){
						swal({
							title: "Hold up!",
							text: response.message,
							icon: "error",
						});
					}else if(response.status == 'success'){
						console.log('Logged Out');
						window.location.href = '/';
					}
				}
			});
		}
	});
});

function signInForm(email){
    $('#form-welcome').hide();
    $('#sign-in-email').val(email);
    $('#form-sign-in').show();
    $('#sign-in-password').focus();
}
$('#team-options-join').click(function(event){
	event.preventDefault();
    $('#team-options').hide();
    $('#sign-up-join').show();
});
$('#team-options-create').click(function(event){
	event.preventDefault();
    $('#team-options').hide();
    $('#sign-up-create').show();
});
$('#team-options-solo').click(function(event){
	event.preventDefault();
    $('#team-options').hide();
    $('#sign-up-solo').show();
});


$('.delete-service').click(function(event){
	event.preventDefault();
	var server_slug = $(this).data('slug');
	swal({
		icon: "warning",
		title: "Are you sure?",
		text: "This will delete the service from the database. This action cannot be undone.",
		buttons: {
			cancel: 'Cancel',
			confirm: 'Delete Service',
		},
		dangerMode: true,
	}).then((willDelete) => {
		if (willDelete) {
			$.ajax({
				type: "POST",
				url: '/process.php',
				data: "action=service-delete&slug="+server_slug,
				dataType: 'json',
				success: function(response){
					console.log(response);
					if(response.status == 'error'){
						swal({
							title: "Hold up!",
							text: response.message,
							icon: "error",
						});
					}else if(response.status == 'success'){
						window.location.href = '/servers';
					}
				}
			});
		}
	});
});


$('.flyout-overlay').click(function(event){
	event.preventDefault();
	$('.flyout.active').removeClass('active');
	$('.flyout-overlay').removeClass('active');
});
$('.flyout-close').click(function(event){
	event.preventDefault();
	$('.flyout.active').removeClass('active');
	$('.flyout-overlay').removeClass('active');
});
/*
$('.flyout-site_new').click(function(event){
	event.preventDefault();

	var server_slug = $(this).data('server');
	$('#flyout-form__site-new').trigger("reset");
	$('#flyout-form__site-new').find('.form-control').removeClass( "--invalid" );
	$('#flyout-form__site-new').find('.form-select').removeClass( "--invalid" );

	$('#client-search').val('');

	$('input[name="client_type"]').val('');
	$('input[name="client_value"]').val('');

	$('.client-selected .type').html('');
	$('.client-selected .value').html('');

	$('.client-options').hide();
	$('#client-input').show();
	$('#client-selected').hide();

	$('#form-select__server-list').html(`
	<option value="">Please Select</option>
	`);
	$.getJSON('/api/server-list.json',function(data){
		$.each(data,function(key, value){
			$('#form-select__server-list').append(`
			<option value="`+value['server_slug']+`">`+value['server_name']+` (`+value['ip_address']+`)</option>
			`);
		});
		if(server_slug){
			$('#form-select__server-list').val(server_slug);
		}
	});

	$('#flyout-site_new').addClass('active');
	$('.flyout-overlay').addClass('active');
});*/
$('.flyout-vehicle_new').click(function(event){
	event.preventDefault();
	$('#flyout-form__vehicle_new').trigger("reset");
	$('#flyout-form__vehicle_new').find('.form-control').removeClass( "--invalid" );
	$('#flyout-form__vehicle_new').find('.form-select').removeClass( "--invalid" );

	$('#flyout-vehicle_new').addClass('active');
	$('.flyout-overlay').addClass('active');
});
$('.flyout-booking_new').click(function(event){
	event.preventDefault();
	$('#flyout-form__booking_new').trigger("reset");
	$('#flyout-form__booking_new').find('.form-control').removeClass( "--invalid" );
	$('#flyout-form__booking_new').find('.form-select').removeClass( "--invalid" );

	$('#flyout-booking_new').addClass('active');
	$('.flyout-overlay').addClass('active');
});
$('.flyout-item_add').click(function(event){
	event.preventDefault();
	$('#flyout-form__item_add').trigger("reset");
	$('#flyout-form__item_add').find('.form-control').removeClass( "--invalid" );
	$('#flyout-form__item_add').find('.form-select').removeClass( "--invalid" );

	$('#flyout-item_add').addClass('active');
	$('.flyout-overlay').addClass('active');
});
$('.flyout-labour_add').click(function(event){
	event.preventDefault();
	$('#flyout-form__labour_add').trigger("reset");
	$('#flyout-form__labour_add').find('.form-control').removeClass( "--invalid" );
	$('#flyout-form__labour_add').find('.form-select').removeClass( "--invalid" );

	$('#flyout-labour_add').addClass('active');
	$('.flyout-overlay').addClass('active');
});


// Get the input box
let clientSearch = document.getElementById('client-search');
// Init a timeout variable to be used below
let timeout = null;
var processClientSearch = function(e){
	clearTimeout(timeout);

    // Make a new timeout set to go off in 1000ms (1 second)
    timeout = setTimeout(function () {
		var query = clientSearch.value;
		if(query.length >= 1){
		$.ajax({
			type: "GET",
			url: '/api/client-search.json',
			data: 'query='+encodeURIComponent(query),
			dataType: 'json',
			success: function(response){
				//console.log(response);
				console.log(response);
				$(".client-options .button").html("<span>Create new Client</span>"+query);
				$('.client-select').html('');
				var results = response.clients;
				if(results.length < 1){
					$('.client-select').hide();
					$('.client-select__heading').hide();
				}else{
					$('.client-select').show();
					$('.client-select__heading').show();
				}

				results.forEach(function(result){
					var client_name = result.client_name;
					var client_id = result.client_id;
					var client_slug = result.client_slug;
					$('.client-select').append(`
					<button type="button" data-client="`+client_slug+`">`+client_name+`</button>
					`);
				});
				$(".client-options").show();
			}
		});
	}else{
		$(".client-results").hide();
	}
    }, 500);
};
if(clientSearch){
	clientSearch.addEventListener('keyup', processClientSearch);
	clientSearch.addEventListener('click', processClientSearch);
}

$('#client-create-button').click(function(event){
	event.preventDefault();

	var value = $('#client-search').val();

	$('input[name="client_type"]').val('create');
	$('input[name="client_value"]').val(value);

	$('.client-selected .type').html('Create New:');
	$('.client-selected .value').html(value);

	
	$('#client-input').hide();
	$('#client-selected').show();

});


$( document ).on('click','.client-select button',function(event){
	event.preventDefault();
	var client_slug = $(this).data('client');
	var client_name = $(this).html();

	$('input[name="client_type"]').val('select');
	$('input[name="client_value"]').val(client_slug);

	$('.client-selected .type').html('Select Existing:');
	$('.client-selected .value').html(client_name);

	
	$('#client-input').hide();
	$('#client-selected').show();
});

$('.client-selected-cancel').click(function(event){
	event.preventDefault();

	$('#client-search').val('');

	$('input[name="client_type"]').val('');
	$('input[name="client_value"]').val('');

	$('.client-selected .type').html('');
	$('.client-selected .value').html('');

	$('.client-options').hide();
	$('#client-input').show();
	$('#client-selected').hide();

});

$( document ).on('click','.domain-list .item .row',function(event){
	event.preventDefault();
	//alert("TEST");
	$('.domain-list .item').removeClass('--active');
	$(this).parent().addClass('--active');
});

$( document ).on('click','.team-members .item .row',function(event){
	event.preventDefault();
	//alert("TEST");
	$('.team-members .item').removeClass('--active');
	$(this).parent().addClass('--active');
});


$( document ).on('click','.domain_remove_button',function(event){
	event.preventDefault();

	var domain_name = $(this).data('domain-name');
	var domain_slug = $(this).data('domain-slug');
	var site_slug = $(this).data('site-slug');

	swal({
		title: "Are you sure?",
		text: "Are you sure you want to remove the Domain ("+domain_name+") from this Site?",
		buttons: {
			cancel: 'Cancel',
			confirm: 'Remove Domain',
		},
		dangerMode: true,
		icon: 'warning',
	}).then((willDelete) => {
		if (willDelete) {
			$.ajax({
				type: "POST",
				url: '/process.php',
				data: "action=domain-remove&domain_slug="+domain_slug+"&site_slug="+site_slug,
				dataType: 'json',
				success: function(response){
					console.log(response);
					if(response.status == 'error'){
						swal({
							title: "Hold up!",
							text: response.message,
							icon: "error",
						});
					}else if(response.status == 'success'){
						window.location.reload();
					}
				}
			});
		}
	});
});

$('.refresh_dns_button').click(function(event){
	event.preventDefault();
	$(this).addClass('--processing');

	var site_slug = $(this).data('site-slug');

	$.ajax({
		type: "POST",
		url: '/process.php',
		data: "action=refresh-dns-site&site_slug="+site_slug,
		dataType: 'json',
		success: function(response){
			console.log(response);
			if(response.status == 'error'){
				swal({
					title: "Hold up!",
					text: response.message,
					icon: "error",
				});
			}else if(response.status == 'success'){
				window.location.reload();
			}
		}
	});
});

$( document ).on('click','.site_delete_button',function(event){
	event.preventDefault();

	var name = $(this).data('name');
	var slug = $(this).data('slug');

	swal({
		title: "Are you sure?",
		text: "Are you sure you want to delete the Site "+name+"? This cannot be undone.",
		buttons: {
			cancel: 'Cancel',
			confirm: 'Delete Site',
		},
		dangerMode: true,
		icon: 'warning',
	}).then((willDelete) => {
		if (willDelete) {
			$.ajax({
				type: "POST",
				url: '/process.php',
				data: "action=site-delete&site_slug="+slug,
				dataType: 'json',
				success: function(response){
					console.log(response);
					if(response.status == 'error'){
						swal({
							title: "Hold up!",
							text: response.message,
							icon: "error",
						});
					}else if(response.status == 'success'){
						window.location.href = '/sites';
					}
				}
			});
		}
	});
});
$( document ).on('click','.account-delete-button',function(event){
	event.preventDefault();

	swal({
		title: "Warning!",
		text: "Deleting your Account is a permanent action that cannot be undone. If you are the Team Owner, you'll need to transfer ownership to another Team Member before you can delete your Account.",
		buttons: {
			cancel: 'Cancel',
			confirm: 'Delete Account',
		},
		dangerMode: true,
	}).then((willDelete) => {
		if (willDelete) {
			swal({
				title: "Are you sure?",
				text: "Are you absolutely sure you want to proceed with deleting your Account? This is your last chance to cancel!",
				buttons: {
					cancel: 'Cancel',
					confirm: 'Delete Account',
				},
				dangerMode: true,
				icon: 'warning',
			}).then((willDelete) => {
				if (willDelete) {
					$.ajax({
						type: "POST",
						url: '/process.php',
						data: "action=account-delete",
						dataType: 'json',
						success: function(response){
							console.log(response);
							if(response.status == 'error'){
								swal({
									title: "Hold up!",
									text: response.message,
									icon: "error",
								});
							}else if(response.status == 'success'){
								window.location.reload();
							}
						}
					});
				}
			});
		}
	});
});
$( document ).on('click','.team-delete-button',function(event){
	event.preventDefault();

	swal({
		title: "Warning!",
		text: "Deleting a team is a permanent action that cannot be undone. This will delete all data associated with the team, including all Servers, Sites, Domains, and Team Members.",
		buttons: {
			cancel: 'Cancel',
			confirm: 'Delete Team & All Data',
		},
		dangerMode: true,
	}).then((willDelete) => {
		if (willDelete) {
			swal({
				title: "Are you sure?",
				text: "Are you absolutely sure you want to proceed with deleting your team? This is your last chance to cancel!",
				buttons: {
					cancel: 'Cancel',
					confirm: 'Delete Team & All Data',
				},
				dangerMode: true,
				icon: 'warning',
			}).then((willDelete) => {
				if (willDelete) {
					$.ajax({
						type: "POST",
						url: '/process.php',
						data: "action=team-delete",
						dataType: 'json',
						success: function(response){
							console.log(response);
							if(response.status == 'error'){
								swal({
									title: "Hold up!",
									text: response.message,
									icon: "error",
								});
							}else if(response.status == 'success'){
								window.location.reload();
							}
						}
					});
				}
			});
		}
	});
});

$( document ).on('click','.session-row',function(event){
	event.preventDefault();

	var token = $(this).data('token');
	var device = $(this).data('device');

	swal({
		title: "Are you sure?",
		text: "Are you sure you want to close the Session ("+device+")? ServerBook will be logged out on that device.",
		buttons: {
			cancel: 'Cancel',
			confirm: 'Close Session',
		},
		icon: 'warning',
		dangerMode: true,
	}).then((willDelete) => {
		if (willDelete) {
			//$(this).remove();
			var thisRow = $(this);
			$.ajax({
				type: "POST",
				url: '/process.php',
				data: "action=session-close&token="+token,
				dataType: 'json',
				success: function(response){
					console.log(response);
					if(response.status == 'error'){
						swal({
							title: "Hold up!",
							text: response.message,
							icon: "error",
						});
					}else if(response.status == 'success'){
						thisRow.remove();
						if(response.current == true){
							window.location.reload();
						}
					}
				}
			});
		}
	});
});
$( document ).on('click','.session-all-button',function(event){
	event.preventDefault();

	swal({
		title: "Are you sure?",
		text: "Are you sure you want to close all of your Sessions? Your ServerBook account will be logged out on all devices.",
		buttons: {
			cancel: 'Cancel',
			confirm: 'Close all Sessions',
		},
		icon: 'warning',
		dangerMode: true,
	}).then((willDelete) => {
		if (willDelete) {
			$.ajax({
				type: "POST",
				url: '/process.php',
				data: "action=session-close-all",
				dataType: 'json',
				success: function(response){
					console.log(response);
					if(response.status == 'error'){
						swal({
							title: "Hold up!",
							text: response.message,
							icon: "error",
						});
					}else if(response.status == 'success'){
						window.location.reload();
					}
				}
			});
		}
	});
});

$( document ).on('click','.team-members .item .level-select button',function(event){
	event.preventDefault();
	//alert("TEST");
	//Get the data-user attribute from the .item
	var user = $(this).closest('.item').data('user');
	//Get the data-level attribute from the button
	var level = $(this).data('level');

	var thisButton = $(this);

	//Buttons in this item
	var buttons = $(this).closest('.item').find('.level-select button');

	console.log("action=member-level&user="+user+"&level="+level);

	$.ajax({
		type: "POST",
		url: '/process.php',
		data: "action=member-level&user="+user+"&level="+level,
		dataType: 'json',
		success: function(response){
			console.log(response);
			if(response.status == 'error'){
				swal({
					title: "Hold up!",
					text: response.message,
					icon: "error",
				});
			}else if(response.status == 'success'){
				//Remove active class from all buttons
				buttons.removeClass('active');
				//Add active class to the button that was clicked
				thisButton.addClass('active');

				//New Level label
				if(response.level == 1){
					var level_label = 'Admin';
				}else if(response.level == 2){
					var level_label = 'Editor';
				}else if(response.level == 3){
					var level_label = 'Viewer';
				}
				//Update the level text
				$('.team-members .item[data-user="'+user+'"] .level_label').text(level_label);
			}
		}
	});
});

$( document ).on('click','.member-owner-button',function(event){
	event.preventDefault();

	//Get the data-user attribute from the .item
	var user = $(this).closest('.item').data('user');
	var name = $(this).closest('.item').data('name');

	swal({
		title: "Are you sure?",
		text: "Are you sure you want to transfer ownership of this team to "+name+"? You will no longer possess owner privileges.",
		buttons: {
			cancel: 'Cancel',
			confirm: 'Transfer Ownership',
		},
		icon: 'warning',
	}).then((willDelete) => {
		if (willDelete) {
			$.ajax({
				type: "POST",
				url: '/process.php',
				data: "action=member-transfer&user="+user,
				dataType: 'json',
				success: function(response){
					console.log(response);
					if(response.status == 'error'){
						swal({
							title: "Hold up!",
							text: response.message,
							icon: "error",
						});
					}else if(response.status == 'success'){
						window.location.reload();
					}
				}
			});
		}
	});
});

$( document ).on('click','.member-remove-button',function(event){
	event.preventDefault();

	//Get the data-user attribute from the .item
	var user = $(this).closest('.item').data('user');
	var name = $(this).closest('.item').data('name');

	swal({
		title: "Are you sure?",
		text: "Are you sure you want to remove "+name+" from the team? This action cannot be undone.",
		buttons: {
			cancel: 'Cancel',
			confirm: 'Remove Member',
		},
		icon: 'warning',
		dangerMode: true,
	}).then((willDelete) => {
		if (willDelete) {
			$.ajax({
				type: "POST",
				url: '/process.php',
				data: "action=member-remove&user="+user,
				dataType: 'json',
				success: function(response){
					console.log(response);
					if(response.status == 'error'){
						swal({
							title: "Hold up!",
							text: response.message,
							icon: "error",
						});
					}else if(response.status == 'success'){
						window.location.reload();
					}
				}
			});
		}
	});
});