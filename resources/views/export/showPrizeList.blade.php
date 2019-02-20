<!DOCTYPE html>
<html>
<head>
	<title>{{$show->title}}</title>
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
					@if(isset($show))
						<td><b>{{$show->title}}</b></td>
					@endif
				</tr>
			@foreach($showAssetList as $prizeList)
				<tr>
					<td><b>{{ getAssetName($prizeList->assets) }}</b></td>
				</tr>
				@if(isset($prizeList->position_fields))
				@php
					$prizeMoney = json_decode($prizeList->position_fields);
				@endphp
					@if(count($prizeMoney)>0)
						@foreach($prizeMoney as $position)
						@if(isset($position->position))
						<tr>
							<td><b>{!! getPostionText($position->position) !!}</b></td>
							<td>{{getpriceFormate($position->price)}}</td>
							@if(isset($position->horse_id))
							<td>{!! getHorseNameAndUserfromid($position->horse_id,$prizeList->asset_id,$show->id) !!}</td>
							@else
							<td>No Horse Set</td>
							@endif
						</tr>
						@endif
						@endforeach
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