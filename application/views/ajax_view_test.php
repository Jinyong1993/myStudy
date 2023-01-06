<html>

<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="//code.jquery.com/jquery.min.js"></script>
<script>
	function add() {
		$.ajax({
			url: "ajax_controller",
			type: "post",
			data: $("form").serialize(),
		}).done(function(data) {
			alert(data);
		});
	};
</script>

</head>
<body>
<form>
	<input type="text" name="number1"> 더하기 <input type="text" name="number2"><br>
	<input type="button" onclick="add()" value="결과">
</form>
</body>

</html>