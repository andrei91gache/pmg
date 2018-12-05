<div class="panel">
	<h3><i class="icon icon-users"></i> {l s='Personnes autorisées à recevoir une alerte'}</h3>
	<form class="defaultForm form-horizontal" id="configuration_form" method="post" enctype="multipart/form-data" novalidate>
		<div class="row">
			{if $getProfileUser eq 'SuperAdmin'}
			<div class="col-lg-12">
				<div class="col-md-10">
					<div class="col-md-5">
						<label class="control-label" > {l s='Emails'} </label>
					</div>
					<div class="col-md-7">
						<div id="Permission-email" name="permission-email" class="comptesoc" style="height:auto;">
						</div>
					</div>
				</div>
				<div class="col-md-10">
					<div class="col-md-5">
						<label class="control-label" > {l s='Hangouts'} </label>
					</div>
					<div class="col-md-7">
						<div id="Permission-hangouts" name="permission-hangouts" class="comptesoc" style="height:auto;">
						</div>
					</div>
				</div>
				<div class="col-md-10">
					<div class="col-md-5">
						<label class="control-label" > {l s='SMS'} </label>
					</div>
					<div class="col-md-7">
						<div id="Permission-sms" name="permission-sms" class="comptesoc" style="height:auto;">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel-footer">
			<button type="submit" value="1" id="configuration_form_submit_btn" name="VAR" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save'}</button>
		</div>
		{else}
		<code>Vous n'avez pas accès à ces paramètres, c'est uniquement pour SuperAdmin</code>
		</div>
		{/if}
	</form>
</div>
<script>
    $(function() {
        {ldelim}
            $('#Permission-email').magicSuggest({
                allowFreeEntries: false,
                placeholder: 'Sélectionner les employés',
                valueField: 'id_employee',
                displayField: 'fullname',
                value: {$getConfigEmail},
                data: {$getUsers}
            });
            $('#Permission-hangouts').magicSuggest({
                allowFreeEntries: false,
                placeholder: 'Sélectionner les employés',
                valueField: 'id_employee',
                displayField: 'fullname',
                value: {$getConfigHangouts},
                data: {$getUsers}
            });
            $('#Permission-sms').magicSuggest({
                allowFreeEntries: false,
                placeholder: 'Sélectionner les employés',
                valueField: 'id_employee',
                displayField: 'fullname',
                value: {$getConfigSms},
                data: {$getUsers}
            });

			{rdelim}
    });
</script>
