@if (isset($customFields) and $customFields->count() > 0)
	<div class="row" id="customFields">
		<div class="col-xl-12">
			<div class="row pl-2 pr-2">
				<div class="col-xl-12 pb-2 pl-1 pr-1">
					<h4> {{ t('Additional Details') }}</h4>
				</div>
			</div>
		</div>
		
		<div class="col-xl-12">
			<div class="row pl-2 pr-2">
				@foreach($customFields as $field)
					<?php
					if (in_array($field->type, ['radio', 'select'])) {
						if (is_numeric($field->default)) {
							$option = \App\Models\FieldOption::findTrans($field->default);
							if (!empty($option)) {
								$field->default = $option->value;
							}
						}
					}
					if (in_array($field->type, ['checkbox'])) {
						$field->default = ($field->default == 1) ? t('Yes') : t('No');
					}
					?>
					@if ($field->type == 'file')
						<div class="detail-line col-xl-12 pb-2 pl-1 pr-1">
							<div class="rounded-small ml-0 mr-0 p-2 no-padding">
								<div class="post-resume">
									<span class="detail-line-label"><h4>{{ $field->name }}</h4></span>
								</div>
								<div class="post-resume">
									<span class="detail-line-value">
										<a class="btn btn-default" href="{{ fileUrl($field->default) }}" target="_blank">
											{{ t('Download') }}
										</a>
									</span>
								</div>
							</div>
						</div>
					@else
						@if (!is_array($field->default))
							<div class="detail-line col-sm-6 col-xs-12 pb-2 pl-1 pr-1">
								<div class="dotted no-left-padding">
									<span class="detail-line-label weight400">{{ $field->name }}</span>
									<span class="dots"></span>
									<span class="detail-line-value">{{ $field->default }}</span>
								</div>
							</div>
						@else
							@if (count($field->default) > 0)
								@if($field->name != "Features")
									<div class="detail-line col-sm-6 col-xs-12 pb-2 pl-1 pr-1 white">
										<div class="dotted no-left-padding">
											<span class="detail-line-label weight400">{{ $field->name }}</span>
											<span class="dots"></span>
											<span class="detail-line-value">
												@foreach($field->default as $valueItem)
													@continue(!isset($valueItem->value))
														{{ $valueItem->value }}
												@endforeach
											</span>
										</div>
									</div>
								@endif
							@endif
						@endif
					@endif
				@endforeach

				<!-- Featurs -->
				@foreach($customFields as $field)
				<?php
					$step = 0;
				?>
					@if ($field->type != 'file')
						@if (is_array($field->default))
							@if (count($field->default) > 0)
								@if($field->name == "Features")
									<div class="detail-line col-xl-12 pb-2 pl-1 pr-1">
										<h4>{{ $field->name }}</h4>
										<div class="row m-0 p-2 white no-left-padding">
											@foreach($field->default as $valueItem)
												@continue(!isset($valueItem->value))
												<div class="detail-line col-sm-6 col-xs-12 pb-2 pl-1 pr-1 white no-left-padding">
														@if( ($step % 2) != 0 && ($step != 0) )
															<span class="detail-line-label weight400 left second">
																{{ $valueItem->value }}
															</span>
														@else
															<span class="detail-line-label weight400 left ">
																{{ $valueItem->value }}
															</span>
														@endif
														<?php $step++; ?>
												</div>
											@endforeach
										</div>
									</div>
								@endif
							@endif
						@endif
					@endif
				@endforeach
			</div>
		</div>
	</div>
@endif
