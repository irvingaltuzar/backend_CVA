<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		{{ Html::style('scss/ticket.scss'); }}
		{{-- {{ Html::style('css/qr.css'); }} --}}
		{{ Html::script('js/qr.js'); }}
		<link href='//fonts.googleapis.com/css?family=Lato:400,300,100,700,900' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<div class="cardWrap">
			<div class="card cardLeft">
			<div class="title">
				@if ($is_validate)
					<h2 style="color:green;">Válido</h2>
					<span>Departamento</span>
				@else
					<h2 style="color:red;">Vencido</h2>
					<span>Departamento</span>
				@endif
			</div>
			<h1>{{ $work_permit->type->description }}</h1>
			<div class="name">
				<h2>{{ $work_permit->user->user_name }}</h2>
				<span>Responsable</span>
			</div>

			</div>
			<div class="card cardRight">
				<div class="eye"></div>
				<div class="number">
					<h3>{{ \Carbon\Carbon::parse($work_permit->start)->format('d-m-Y H:i:s') }}</h3>
				</div>
				<hr>
				<div class="number">
					<h3>{{ \Carbon\Carbon::parse($work_permit->end)->format('d-m-Y H:i:s') }}</h3>
				</div>
			</div>

		</div>
	</body>
</html>
