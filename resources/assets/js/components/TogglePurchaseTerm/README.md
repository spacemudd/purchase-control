# Toggle Purchase Term - Usage

	<toggle-purchase-term :term-id.number="{{ $term->id }}"
						   :po-id.number="{{ $po->id }}"
						   :enabled-prop.number="{{ $po->terms->contains($term->id) ? 'true' : 'false' }}"
						   >
		{{ $term->name }}
	</toggle-purchase-term>
