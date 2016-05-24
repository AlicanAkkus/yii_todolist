<script>
			function redirect = setTimeout(function(){
				windows.location.href='http://advanced.com/admin/todo/todo/';
			}, 5000);
</script>
<div class="todo-default-index">
    
	<div class="container">
		<h4>Ulaştığınız controller : <?= $this->context->action->uniqueId ?></h4>
		Yönlendiriliyorsunuz, beklemek istemeden Todo'lara erişmek için <a href="http://advanced.com/admin/todo/todo/" class="btn btn-default">tıklayınız.</a>
		<script>
			redirect();
		</script>

	</div>	
	
	
</div>
