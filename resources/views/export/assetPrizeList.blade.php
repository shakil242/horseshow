<!DOCTYPE html>
<html>
<head>
	<title>Classes</title>
</head>
<body>
	<table>
		<!-- <thead>
			<th>Name</th>
			<th>E-mail</th>
		</thead> -->
		<tbody>
				<tr>
					<td><i>Classes/Placements</i></td>
					<td><i>Prize Money</i></td>
					@if(isset($shows))
						@foreach($shows as $show)
							<td><b>{{$show->title}}</b></td>
						@endforeach
					@endif
				</tr>
			@foreach($assets as $asset)
				<tr>
					<td><b>{{ getAssetName($asset) }}</b></td>
				</tr>
				@if(isset($asset->showPrizing))
				
					@php
						if(isset($asset->showPrizing->fields)){
							$showPrizing = json_decode($asset->showPrizing->fields);
						}else{
							$showPrizing = null;
						}
					@endphp
					@if(isset($showPrizing->place))
						@foreach($showPrizing->place as $prizeMoney)
							<tr>
								<td>
									{!! getPostionText($prizeMoney->position) !!}
								</td>
								<td>
									{{getpriceFormate($prizeMoney->price)}}
								</td>
							</tr>
						@endforeach
					@else
						<tr><td>Please fix the values and save it again.</td></tr>
					@endif

				@else
				<tr>
					<td>No Prizing List</td>
				</tr>
				@endif
			@endforeach
		</tbody>
	</table>
</body>
</html>