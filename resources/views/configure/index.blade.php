<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>config変換</title>
	{{-- style --}}
	<style>
		button{margin: 10px;}
		div{margin: 10px;}
	</style>
	{{-- style --}}
</head>
<body>
<h1>config変換(仮)</h1>
	<form method="POST" action="/" enctype="multipart/form-data">

		{{ csrf_field() }}

    {{-- <input type="file" id="file" name="file" class="form-control"> --}}
	<div><input type="file" id="file" name="file1" class="form-control" multiple>
	<select name="blood">
		<option value="cisco">cisco系</option>
		<option value="nexus">Nexus</option>
		<option value="AB">AB型</option>
	</select></div>
	<div><input type="file" id="file" name="file2" class="form-control" multiple>
	<select name="blood">
		<option value="cisco">cisco系</option>
		<option value="nexus">Nexus</option>
		<option value="AB">AB型</option>
	</select></div>
	<div><input type="file" id="file" name="file3" class="form-control" multiple>
	<select name="blood">
		<option value="cisco">cisco系</option>
		<option value="nexus">Nexus</option>
		<option value="AB">AB型</option>
	</select></div>
<br>
	<button type="submit">変換</button>

	</form>

</body>
</html>