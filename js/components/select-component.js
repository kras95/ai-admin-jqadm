/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
 */


Aimeos.components['select-component'] = {
	template: `
		<select @update:modelValue="$emit('update:modelValue', $event.target.value)" v-bind:value="modelValue">
			<option v-if="text" value="">{{ text }}</option>
			<option v-if="modelValue && !items[modelValue]" v-bind:value="modelValue">{{ modelValue }}</option>
			<option v-if="all" v-bind:value="null" v-bind:selected="modelValue === null">{{ all }}</option>
			<option v-for="(label, key) in items" v-bind:key="key" v-bind:value="key">
				{{ label || key }}
			</option>
		</select>
	`,
	emits: ['update:modelValue'],
	props: {
		'all': {type: String, default: ''},
		'items': {type: Object, required: true},
		'text': {type: String, default: ''},
		'modelValue': {required: true}
	}
};
