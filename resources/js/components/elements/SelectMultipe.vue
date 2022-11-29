<template>
    <div
      ref="multiselect"
      :class="classList.container"
      :id="searchable ? undefined : id"
      :dir="rtl ? 'rtl' : undefined"
      @focusin="handleFocusIn"
      @focusout="handleFocusOut"
      @keyup="handleKeyup"
      @keydown="handleKeydown"
    >
      <div
        :class="classList.wrapper"
        @mousedown="handleMousedown"
        ref="wrapper"
  
        :tabindex="tabindex"
        :aria-controls="!searchable ? ariaControls : undefined"
        :aria-placeholder="!searchable ? ariaPlaceholder : undefined"
        :aria-expanded="!searchable ? isOpen : undefined"
        :aria-activedescendant="!searchable ? ariaActiveDescendant : undefined"
        :aria-multiselectable="!searchable ? ariaMultiselectable : undefined"
        :role="!searchable ? 'combobox' : undefined"
        v-bind="!searchable ? arias : {}"
      >
        <!-- Search -->
        <template v-if="mode !== 'tags' && searchable && !disabled">
          <input
            :type="inputType"
            :modelValue="search"
            :value="search"
            :class="classList.search"
            :autocomplete="autocomplete"
            :id="searchable ? id : undefined"
            @input="handleSearchInput"
            @keypress="handleKeypress"
            @paste.stop="handlePaste"
            ref="input"
  
            :aria-controls="ariaControls"
            :aria-placeholder="ariaPlaceholder"
            :aria-expanded="isOpen"
            :aria-activedescendant="ariaActiveDescendant"
            :aria-multiselectable="ariaMultiselectable"
            role="combobox"
  
            v-bind="{
              ...attrs,
              ...arias,
            }"
          />
        </template>
  
        <!-- Tags (with search) -->
        <template v-if="mode == 'tags'">
            <div div :class="classList.tags" data-tags>
                <slot
                    v-for="(option, i, key) in iv"
                    name="tag"
                    :option="option"
                    :handleTagRemove="handleTagRemove"
                    :disabled="disabled"
                >
                    <span
                        :class="classList.tag"
                        tabindex="-1"
                        @keyup.enter="handleTagRemove(option, $event)"
                        :key="key"
        
                        :aria-label="ariaTagLabel(option[label])"
                    >
                        {{ option[label] }}
                        <span
                        v-if="!disabled"
                        :class="classList.tagRemove"
                        @click="handleTagRemove(option, $event)"
                        >
                        <span :class="classList.tagRemoveIcon"></span>
                        </span>
                    </span>
                </slot>
        
                <div :class="classList.tagsSearchWrapper" ref="tags">
                <!-- Used for measuring search width -->
                    <span :class="classList.tagsSearchCopy">{{ search }}</span>
        
                    <!-- Actual search input -->
                    <input    
                        v-if="searchable && !disabled"
                        :type="inputType"
                        :modelValue="search"
                        :value="search"
                        :class="classList.tagsSearch"
                        :id="searchable ? id : undefined"
                        :autocomplete="autocomplete"
                        @input="handleSearchInput"
                        @keypress="handleKeypress"
                        @paste.stop="handlePaste"
                        ref="input"
                        
                        :aria-controls="ariaControls"
                        :aria-placeholder="ariaPlaceholder"
                        :aria-expanded="isOpen"
                        :aria-activedescendant="ariaActiveDescendant"
                        :aria-multiselectable="ariaMultiselectable"
                        role="combobox"
        
                        v-bind="{
                        ...attrs,
                        ...arias,
                        }"
                    />
                </div>
            </div>
        </template>
  
        <!-- Single label -->
        <template v-if="mode == 'single' && hasSelected && !search && iv">
          <slot name="singlelabel" :value="iv">
            <div :class="classList.singleLabel">
              <span :class="classList.singleLabelText">{{ iv[label] }}</span>
            </div>
          </slot>
        </template>
  
        <!-- Multiple label -->
        <template v-if="mode == 'multiple' && hasSelected && !search">
          <div :class="classList.multipleLabel">
            <slot name="multiplelabel" :values="iv"></slot>
          </div>
        </template>
  
        <!-- Placeholder -->
        <template v-if="placeholder && !hasSelected && !search">
          <slot name="placeholder">
            <div :class="classList.placeholder" aria-hidden="true">
              {{ placeholder }}
            </div>
          </slot>
        </template>
  
        <!-- Spinner -->
        <slot v-if="loading || resolving" name="spinner">
          <span :class="classList.spinner" aria-hidden="true"></span>
        </slot>
  
        <!-- Clear -->
        <slot v-if="hasSelected && !disabled && canClear && !busy" name="clear" :clear="clear">
          <span
            aria-hidden="true"
            tabindex="0"
            role="button"
            data-clear
            aria-roledescription="❎"
            :class="classList.clear"
            @click="clear"
            @keyup.enter="clear"
          ><span :class="classList.clearIcon"></span></span>
        </slot>
  
        <!-- Caret -->
        <slot v-if="caret && showOptions" name="caret">
          <span :class="classList.caret" @click="handleCaretClick" aria-hidden="true"></span>
        </slot>
      </div>
  
      <!-- Options -->
    <div
    :class="classList.dropdown"
    tabindex="-1"
    >
        
        <slot name="beforelist" :options="fo"></slot>
  
        <ul :class="classList.options" :id="ariaControls" role="listbox">
            <template v-if="groups">
                <li
                    v-for="(group, i, key) in fg"
                    :class="classList.group"
                    :key="key"
        
                    :id="ariaGroupId(group)"
                    :aria-label="ariaGroupLabel(group)"
                    :aria-selected="isSelected(group)"
                    role="option"
                >
                <div
                    :class="classList.groupLabel(group)"
                    :data-pointed="isPointed(group)"
                    @mouseenter="setPointer(group, i)"
                    @click="handleGroupClick(group)"
                >
                    <slot name="grouplabel" :group="group" :is-selected="isSelected" :is-pointed="isPointed">
                    <span v-html="group[groupLabel]"></span>
                    </slot>
                </div>
    
                <ul
                    :class="classList.groupOptions"
                    
                    :aria-label="ariaGroupLabel(group)"
                    role="group"
                >
                    <li
                    v-for="(option, i, key) in group.__VISIBLE__"
                    :class="classList.option(option, group)"
                    :data-pointed="isPointed(option)"
                    :data-selected="isSelected(option) || undefined"
                    :key="key"
                    @mouseenter="setPointer(option)"
                    @click="handleOptionClick(option)"
    
                    :id="ariaOptionId(option)"
                    :aria-selected="isSelected(option)"
                    :aria-label="ariaOptionLabel(option)"
                    role="option"
                    >
                    <slot name="option" :option="option" :is-selected="isSelected" :is-pointed="isPointed" :search="search">
                        <span>{{ option[label] }}</span>
                    </slot>
                    </li>
                </ul>
                </li>
            </template>
            <template v-else>
                <li
                    v-for="(option, i, key) in fo"
                    :class="classList.option(option)"
                    :data-pointed="isPointed(option)"
                    :data-selected="isSelected(option) || undefined"
                    :key="key"
                    @mouseenter="setPointer(option)"
                    @click="handleOptionClick(option)"
        
                    :id="ariaOptionId(option)"
                    :aria-selected="isSelected(option)"
                    :aria-label="ariaOptionLabel(option)"
                    role="option"
                >
                    <slot name="option" :option="option" :isSelected="isSelected" :is-pointed="isPointed" :search="search">
                        <span>{{ option[label] }}</span>
                    </slot>
                </li>
            </template>
        </ul>
  
        <slot v-if="noOptions" name="nooptions">
          <div :class="classList.noOptions" v-html="noOptionsText"></div>
        </slot>
  
        <slot v-if="noResults" name="noresults">
          <div :class="classList.noResults" v-html="noResultsText"></div>
        </slot>
  
        <div v-if="infinite && hasMore" :class="classList.inifinite" ref="infiniteLoader">
          <slot name="infinite">
            <span :class="classList.inifiniteSpinner"></span>
          </slot>
        </div>
  
        <slot name="afterlist" :options="fo"></slot>
      </div>
  
      <input v-if="required" :class="classList.fakeInput" tabindex="-1" :value="textValue" required/>
      
      <template v-if="nativeSupport">
        <input v-if="mode == 'single'" type="hidden" :name="name" :value="plainValue !== undefined ? plainValue : ''" />
        <template v-else>
          <input type="hidden" :name="name" :value="plainValue" />
        </template>
      </template>
  
      <div v-if="searchable && hasSelected" :class="classList.assist" :id="ariaAssist" aria-hidden="true">
        {{ ariaLabel }}
      </div>
  
      <div :class="classList.spacer"></div>
  
    </div>
  </template>
  
  <script>
  
    import useData from '@vueform/multiselect/src/composables/useData'
    import useValue from '@vueform/multiselect/src/composables/useValue'
    import useSearch from '@vueform/multiselect/src/composables/useSearch'
    import usePointer from '@vueform/multiselect/src/composables/usePointer'
    import useOptions from '@vueform/multiselect/src/composables/useOptions'
    import usePointerAction from '@vueform/multiselect/src/composables/usePointerAction'
    import useDropdown from '@vueform/multiselect/src/composables/useDropdown'
    import useMultiselect from '@vueform/multiselect/src/composables/useMultiselect'
    import useKeyboard from '@vueform/multiselect/src/composables/useKeyboard' 
    import useClasses from '@vueform/multiselect/src/composables/useClasses' 
    import useScroll from '@vueform/multiselect/src/composables/useScroll' 
    import useA11y from '@vueform/multiselect/src/composables/useA11y' 
  
    import resolveDeps from '@vueform/multiselect/src/utils/resolveDeps'
  
    export default {
      name: 'Multiselect',
      emits: [
        'paste', 'open', 'close', 'select', 'deselect', 
        'input', 'search-change', 'tag', 'option', 'update:modelValue',
        'change', 'clear', 'keydown', 'keyup',
      ],
      props: {
        value: {
          required: false,
        },
        modelValue: {
          required: false,
        },
        options: {
          type: [Array, Object, Function],
          required: false,
          default: () => ([])
        },
        id: {
          type: [String, Number],
          required: false,
        },
        name: {
          type: [String, Number],
          required: false,
          default: 'multiselect',
        },
        disabled: {
          type: Boolean,
          required: false,
          default: false,
        },
        label: {
          type: String,
          required: false,
          default: 'label',
        },
        trackBy: {
          type: String,
          required: false,
          default: undefined,
        },
        valueProp: {
          type: String,
          required: false,
          default: 'value',
        },
        placeholder: {
          type: String,
          required: false,
          default: null,
        },
        mode: {
          type: String,
          required: false,
          default: 'single', // single|multiple|tags
        },
        searchable: {
          type: Boolean,
          required: false,
          default: false,
        },
        limit: {
          type: Number,
          required: false,
          default: -1,
        },
        hideSelected: {
          type: Boolean,
          required: false,
          default: true,
        },
        createTag: {
          type: Boolean,
          required: false,
          default: undefined,
        },
        createOption: {
          type: Boolean,
          required: false,
          default: undefined,
        },
        appendNewTag: {
          type: Boolean,
          required: false,
          default: undefined,
        },
        appendNewOption: {
          type: Boolean,
          required: false,
          default: undefined,
        },
        addTagOn: {
          type: Array,
          required: false,
          default: undefined,
        },
        addOptionOn: {
          type: Array,
          required: false,
          default: undefined,
        },
        caret: {
          type: Boolean,
          required: false,
          default: true,
        },
        loading: {
          type: Boolean,
          required: false,
          default: false,
        },
        noOptionsText: {
          type: String,
          required: false,
          default: 'A lista está vazia',
        },
        noResultsText: {
          type: String,
          required: false,
          default: 'Nem um resultado encontrado',
        },
        multipleLabel: {
          type: Function,
          required: false,
        },
        object: {
          type: Boolean,
          required: false,
          default: false,
        },
        delay: {
          type: Number,
          required: false,
          default: -1,
        },
        minChars: {
          type: Number,
          required: false,
          default: 0,
        },
        resolveOnLoad: {
          type: Boolean,
          required: false,
          default: true,
        },
        filterResults: {
          type: Boolean,
          required: false,
          default: true,
        },
        clearOnSearch: {
          type: Boolean,
          required: false,
          default: false,
        },
        clearOnSelect: {
          type: Boolean,
          required: false,
          default: true,
        },
        canDeselect: {
          type: Boolean,
          required: false,
          default: true,
        },
        canClear: {
          type: Boolean,
          required: false,
          default: true,
        },
        max: {
          type: Number,
          required: false,
          default: -1,
        },
        showOptions: {
          type: Boolean,
          required: false,
          default: true,
        },
        required: {
          type: Boolean,
          required: false,
          default: false,
        },
        openDirection: {
          type: String,
          required: false,
          default: 'bottom',
        },
        nativeSupport: {
          type: Boolean,
          required: false,
          default: false,
        },
        classes: {
          type: Object,
          required: false,
          default: () => ({})
        },
        strict: {
          type: Boolean,
          required: false,
          default: true,
        },
        closeOnSelect: {
          type: Boolean,
          required: false,
          default: true,
        },
        autocomplete: {
          type: String,
          required: false,
        },
        groups: {
          type: Boolean,
          required: false,
          default: false,
        },
        groupLabel: {
          type: String,
          required: false,
          default: 'label',
        },
        groupOptions: {
          type: String,
          required: false,
          default: 'options',
        },
        groupHideEmpty: {
          type: Boolean,
          required: false,
          default: false,
        },
        groupSelect: {
          type: Boolean,
          required: false,
          default: true,
        },
        inputType: {
          type: String,
          required: false,
          default: 'text',
        },
        attrs: {
          required: false,
          type: Object,
          default: () => ({}),
        },
        onCreate: {
          required: false,
          type: Function,
        },
        disabledProp: {
          type: String,
          required: false,
          default: 'disabled',
        },
        searchStart: {
          type: Boolean,
          required: false,
          default: false,
        },
        reverse: {
          type: Boolean,
          required: false,
          default: false,
        },
        regex: {
          type: [Object, String, RegExp],
          required: false,
          default: undefined,
        },
        rtl: {
          type: Boolean,
          required: false,
          default: false,
        },
        infinite: {
          type: Boolean,
          required: false,
          default: false,
        },
        aria: {
          required: false,
          type: Object,
          default: () => ({}),
        },
      },
      setup(props, context)
      { 
        return resolveDeps(props, context, [
          useValue,
          usePointer,
          useDropdown,
          useSearch,
          useData,
          useMultiselect,
          useOptions,
          useScroll,
          usePointerAction,
          useKeyboard,
          useClasses,
          useA11y,
        ])
      }
    }
  </script>
<style>
:root {
    --ms-bg: #f8fafc;
    --ms-border-width: 1px;
    --ms-border-color: #ced4da;
    --ms-radius: 0.25rem;
    --ms-font-size: 0.7875rem;
    --ms-bg-disabled: #e9ecef;
    --ms-line-height: 1.6;
}

.multiselect {
    align-items:center;
    background:var(--ms-bg);
    border:var(--ms-border-width) solid var(--ms-border-color);
    border-radius:var(--ms-radius);
    box-sizing:border-box;
    cursor:pointer;
    display:flex;
    font-size:var(--ms-font-size);
    justify-content:flex-end;
    margin:0 auto;
    min-height:calc(var(--ms-border-width)*2 + var(--ms-font-size)*var(--ms-line-height));
    line-height: var(--ms-line-height);
    outline:none;
    position:relative;
    font-size: 0.7875rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    min-width: 11rem
}
.multiselect.is-open {
    border-radius:var(--ms-radius) var(--ms-radius) 0 0
}
.multiselect.is-open-top {
    border-radius:0 0 var(--ms-radius) var(--ms-radius)
}
.multiselect.is-disabled {
    background:var(--ms-bg-disabled);
    cursor:default
}
.multiselect.is-active {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem #0d6efd40;
}
.multiselect-wrapper {
    align-items:center;
    box-sizing:border-box;
    cursor:pointer;
    display:flex;
    justify-content:flex-end;
    margin:0 auto;
    outline:none;
    position:relative;
    width:100%;
    padding-top: 0.35rem;
    padding-bottom: 0.35rem;
    padding-left: 0.5rem;
}

.multiselect-multiple-label,
.multiselect-placeholder,
.multiselect-single-label {
    align-items:center;
    background:transparent;
    box-sizing:border-box;
    display:flex;
    height:100%;
    left:0;
    line-height:var(--ms-line-height);
    max-width:100%;
    padding-left:var(--ms-px,.875rem);
    padding-right:calc(1.25rem + .875rem *3);
    pointer-events:none;
    position:absolute;
    top:0;
    white-space: nowrap;
    overflow: hidden;
}
.multiselect-placeholder {
    color:var(--ms-placeholder-color,#9ca3af)
}
.multiselect-single-label-text {
    display:block;
    max-width:100%;
    overflow:hidden;
    text-overflow:ellipsis;
    white-space:nowrap
}
.multiselect-search {
    -webkit-appearance:none;
    -moz-appearance:none;
    appearance:none;
    background:var(--ms-bg,#fff);
    border:0;
    border-radius:var(--ms-radius,4px);
    bottom:0;
    box-sizing:border-box;
    font-family:inherit;
    font-size:inherit;
    height:100%;
    left:0;
    outline:none;
    padding-left:var(--ms-px,.875rem);
    position:absolute;
    right:0;
    top:0;
    width:100%
}

.multiselect-tags{
    align-items:center;
    display:flex;
    flex-grow:1;
    flex-shrink:1;
    flex-wrap:wrap;
    margin:var(--ms-tag-my,.25rem) 0 0;
    padding-left:var(--ms-py,.5rem)
}
.multiselect-tag {
    align-items:center;
    background:var(--ms-tag-bg,#10b981);
    border-radius:var(--ms-tag-radius,4px);
    color:var(--ms-tag-color,#fff);
    display:flex;
    font-size:var(--ms-tag-font-size,.875rem);
    font-weight:var(--ms-tag-font-weight,600);
    line-height:var(--ms-tag-line-height,1.25rem);
    margin-bottom:var(--ms-tag-my,.25rem);
    margin-right:var(--ms-tag-mx,.25rem);
    padding:var(--ms-tag-py,.125rem) 0 var(--ms-tag-py,.125rem) var(--ms-tag-px,.5rem);
    white-space:nowrap
}
.multiselect-tag.is-disabled {
    background:var(--ms-tag-bg-disabled,#9ca3af);
    color:var(--ms-tag-color-disabled,#fff);
    padding-right:var(--ms-tag-px,.5rem)
}
.multiselect-tag-remove {
    align-items:center;
    border-radius:var(--ms-tag-remove-radius,4px);
    display:flex;
    justify-content:center;
    margin:var(--ms-tag-remove-my,0) var(--ms-tag-remove-mx,.125rem);
    padding:var(--ms-tag-remove-py,.25rem) var(--ms-tag-remove-px,.25rem)
}
.multiselect-tag-remove:hover {
    background:rgba(0,0,0,.063)
}
.multiselect-tag-remove-icon {
    background-color:currentColor;
    display:inline-block;
    height:.75rem;
    -webkit-mask-image:url("data:image/svg+xml;charset=utf-8,%3Csvg viewBox='0 0 320 512' fill='currentColor' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='m207.6 256 107.72-107.72c6.23-6.23 6.23-16.34 0-22.58l-25.03-25.03c-6.23-6.23-16.34-6.23-22.58 0L160 208.4 52.28 100.68c-6.23-6.23-16.34-6.23-22.58 0L4.68 125.7c-6.23 6.23-6.23 16.34 0 22.58L112.4 256 4.68 363.72c-6.23 6.23-6.23 16.34 0 22.58l25.03 25.03c6.23 6.23 16.34 6.23 22.58 0L160 303.6l107.72 107.72c6.23 6.23 16.34 6.23 22.58 0l25.03-25.03c6.23-6.23 6.23-16.34 0-22.58L207.6 256z'/%3E%3C/svg%3E");
    mask-image:url("data:image/svg+xml;charset=utf-8,%3Csvg viewBox='0 0 320 512' fill='currentColor' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='m207.6 256 107.72-107.72c6.23-6.23 6.23-16.34 0-22.58l-25.03-25.03c-6.23-6.23-16.34-6.23-22.58 0L160 208.4 52.28 100.68c-6.23-6.23-16.34-6.23-22.58 0L4.68 125.7c-6.23 6.23-6.23 16.34 0 22.58L112.4 256 4.68 363.72c-6.23 6.23-6.23 16.34 0 22.58l25.03 25.03c6.23 6.23 16.34 6.23 22.58 0L160 303.6l107.72 107.72c6.23 6.23 16.34 6.23 22.58 0l25.03-25.03c6.23-6.23 6.23-16.34 0-22.58L207.6 256z'/%3E%3C/svg%3E");
    -webkit-mask-position:center;
    mask-position:center;
    -webkit-mask-repeat:no-repeat;
    mask-repeat:no-repeat;
    -webkit-mask-size:contain;
    mask-size:contain;
    opacity:.8;
    width:.75rem
}
.multiselect-tags-search-wrapper {
    display:inline-block;
    flex-grow:1;
    flex-shrink:1;
    height:100%;
    margin:0 var(--ms-tag-mx,4px) var(--ms-tag-my,4px);
    position:relative
}
.multiselect-tags-search-copy {
    display:inline-block;
    height:1px;
    visibility:hidden;
    white-space:pre-wrap;
    width:100%
}
.multiselect-tags-search {
    -webkit-appearance:none;
    -moz-appearance:none;
    appearance:none;
    border:0;
    bottom:0;
    box-sizing:border-box;
    font-family:inherit;
    font-size:inherit;
    left:0;
    outline:none;
    padding:0;
    position:absolute;
    right:0;
    top:0;
    width:100%
}
.multiselect-inifite {
    align-items:center;
    display:flex;
    justify-content:center;
    min-height:calc(var(--ms-border-width, 1px)*2 + var(--ms-font-size, 1rem)*var(--ms-line-height, 1.375) + var(--ms-py, .5rem)*2);
    width:100%
}
.multiselect-inifite-spinner,
.multiselect-spinner {
    animation:multiselect-spin 1s linear infinite;
    background-color:var(--ms-spinner-color,#10b981);
    flex-grow:0;
    flex-shrink:0;
    height:1rem;
    -webkit-mask-image:url("data:image/svg+xml;charset=utf-8,%3Csvg viewBox='0 0 512 512' fill='currentColor' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='m456.433 371.72-27.79-16.045c-7.192-4.152-10.052-13.136-6.487-20.636 25.82-54.328 23.566-118.602-6.768-171.03-30.265-52.529-84.802-86.621-144.76-91.424C262.35 71.922 256 64.953 256 56.649V24.56c0-9.31 7.916-16.609 17.204-15.96 81.795 5.717 156.412 51.902 197.611 123.408 41.301 71.385 43.99 159.096 8.042 232.792-4.082 8.369-14.361 11.575-22.424 6.92z'/%3E%3C/svg%3E");
    mask-image:url("data:image/svg+xml;charset=utf-8,%3Csvg viewBox='0 0 512 512' fill='currentColor' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='m456.433 371.72-27.79-16.045c-7.192-4.152-10.052-13.136-6.487-20.636 25.82-54.328 23.566-118.602-6.768-171.03-30.265-52.529-84.802-86.621-144.76-91.424C262.35 71.922 256 64.953 256 56.649V24.56c0-9.31 7.916-16.609 17.204-15.96 81.795 5.717 156.412 51.902 197.611 123.408 41.301 71.385 43.99 159.096 8.042 232.792-4.082 8.369-14.361 11.575-22.424 6.92z'/%3E%3C/svg%3E");-webkit-mask-position:center;
    mask-position:center;
    -webkit-mask-repeat:no-repeat;
    mask-repeat:no-repeat;
    -webkit-mask-size:contain;
    mask-size:contain;
    width:1rem;
    z-index:10
}
.multiselect-spinner {
    margin:0 var(--ms-px,.875rem) 0 0
}
.multiselect-clear {
    display:flex;
    flex-grow:0;
    flex-shrink:0;
    opacity:1;
    padding:0 var(--ms-px,.875rem) 0 0;
    position:relative;
    transition:.3s;
    z-index:10
}
.multiselect-clear:hover .multiselect-clear-icon {
    background-color:var(--ms-clear-color-hover,#000)
}
.multiselect-clear-icon {
    background-color:var(--ms-clear-color,#999);
    display:inline-block;
    -webkit-mask-image:url("data:image/svg+xml;charset=utf-8,%3Csvg viewBox='0 0 320 512' fill='currentColor' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='m207.6 256 107.72-107.72c6.23-6.23 6.23-16.34 0-22.58l-25.03-25.03c-6.23-6.23-16.34-6.23-22.58 0L160 208.4 52.28 100.68c-6.23-6.23-16.34-6.23-22.58 0L4.68 125.7c-6.23 6.23-6.23 16.34 0 22.58L112.4 256 4.68 363.72c-6.23 6.23-6.23 16.34 0 22.58l25.03 25.03c6.23 6.23 16.34 6.23 22.58 0L160 303.6l107.72 107.72c6.23 6.23 16.34 6.23 22.58 0l25.03-25.03c6.23-6.23 6.23-16.34 0-22.58L207.6 256z'/%3E%3C/svg%3E");
    mask-image:url("data:image/svg+xml;charset=utf-8,%3Csvg viewBox='0 0 320 512' fill='currentColor' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='m207.6 256 107.72-107.72c6.23-6.23 6.23-16.34 0-22.58l-25.03-25.03c-6.23-6.23-16.34-6.23-22.58 0L160 208.4 52.28 100.68c-6.23-6.23-16.34-6.23-22.58 0L4.68 125.7c-6.23 6.23-6.23 16.34 0 22.58L112.4 256 4.68 363.72c-6.23 6.23-6.23 16.34 0 22.58l25.03 25.03c6.23 6.23 16.34 6.23 22.58 0L160 303.6l107.72 107.72c6.23 6.23 16.34 6.23 22.58 0l25.03-25.03c6.23-6.23 6.23-16.34 0-22.58L207.6 256z'/%3E%3C/svg%3E");
    transition:.3s
}
.multiselect-caret,
.multiselect-clear-icon {
    height:1.125rem;
    -webkit-mask-position:center;
    mask-position:center;
    -webkit-mask-repeat:no-repeat;
    mask-repeat:no-repeat;
    -webkit-mask-size:contain;
    mask-size:contain;
    width:.625rem
}
.multiselect-caret {
    background-color:var(--ms-caret-color,#999);
    flex-grow:0;
    flex-shrink:0;
    margin:0 var(--ms-px,.875rem) 0 0;
    -webkit-mask-image:url("data:image/svg+xml;charset=utf-8,%3Csvg viewBox='0 0 320 512' fill='currentColor' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M31.3 192h257.3c17.8 0 26.7 21.5 14.1 34.1L174.1 354.8c-7.8 7.8-20.5 7.8-28.3 0L17.2 226.1C4.6 213.5 13.5 192 31.3 192z'/%3E%3C/svg%3E");
    mask-image:url("data:image/svg+xml;charset=utf-8,%3Csvg viewBox='0 0 320 512' fill='currentColor' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M31.3 192h257.3c17.8 0 26.7 21.5 14.1 34.1L174.1 354.8c-7.8 7.8-20.5 7.8-28.3 0L17.2 226.1C4.6 213.5 13.5 192 31.3 192z'/%3E%3C/svg%3E");
    pointer-events:none;
    position:relative;
    transform:rotate(0deg);
    transition:transform .3s;
    z-index:10
}
.multiselect-caret.is-open {
    pointer-events:auto;
    transform:rotate(180deg)
}
.multiselect-dropdown{
    -webkit-overflow-scrolling:touch;
    background:var(--ms-dropdown-bg,#fff);
    border:var(--ms-dropdown-border-width,1px) solid var(--ms-dropdown-border-color,#d1d5db);
    border-radius:0 0 var(--ms-dropdown-radius,4px) var(--ms-dropdown-radius,4px);
    bottom:0;
    display:flex;
    flex-direction:column;
    left:calc(var(--ms-border-width, 1px)*-1);
    margin-top:calc(var(--ms-border-width, 1px)*-1);
    max-height:15rem;
    max-height:var(--ms-max-height,10rem);
    outline:none;
    overflow-y:scroll;
    position:absolute;
    right:calc(var(--ms-border-width, 1px)*-1);
    transform:translateY(100%);
    z-index:100
}
.multiselect-dropdown.is-top {
    border-radius:var(--ms-dropdown-radius,4px) var(--ms-dropdown-radius,4px) 0 0;
    bottom:auto;
    top:var(--ms-border-width,1px);
    transform:translateY(-100%)
}
.multiselect-dropdown.is-hidden {
    display:none
}
.multiselect-options {
    display:flex;
    flex-direction:column;
    list-style:none;
    margin:0;
    padding:0
}
.multiselect-group {
    margin:0;
    padding:0
}
.multiselect-group-label {
    align-items:center;
    background:var(--ms-group-label-bg,#e5e7eb);
    box-sizing:border-box;
    color:var(--ms-group-label-color,#374151);
    cursor:default;
    display:flex;
    font-size:.875rem;
    font-weight:600;
    justify-content:flex-start;
    line-height:var(--ms-group-label-line-height,1.375);
    padding:var(--ms-group-label-py,.3rem) var(--ms-group-label-px,.75rem);
    text-align:left;text-decoration:none
}
.multiselect-group-label.is-pointable{
    cursor:pointer
}.multiselect-group-label.is-pointed{
    background:var(--ms-group-label-bg-pointed,#d1d5db);
    color:var(--ms-group-label-color-pointed,#374151)
}
.multiselect-group-label.is-selected{
    background:var(--bs-primary,#059669);
    color:var(--ms-group-label-color-selected,#fff)
}
.multiselect-group-label.is-disabled{
    background:var(--ms-group-label-bg-disabled,#f3f4f6);
    color:var(--ms-group-label-color-disabled,#d1d5db);
    cursor:not-allowed
}
.multiselect-group-label.is-selected.is-pointed{
    background:var(--bs-primary,#0c9e70);
    color:var(--ms-group-label-color-selected-pointed,#fff)
}
.multiselect-group-label.is-selected.is-disabled{
    background:var(--bs-primary,#75cfb1);
    color:var(--ms-group-label-color-selected-disabled,#d1fae5)
}
.multiselect-group-options{
    margin:0;
    padding:0
}
.multiselect-option{
    align-items:center;
    box-sizing:border-box;
    cursor:pointer;
    display:flex;
    font-size:var(--ms-option-font-size,1rem);
    justify-content:flex-start;
    line-height:var(--ms-option-line-height,1.375);
    padding:var(--ms-option-py,.5rem) var(--ms-option-px,.75rem);
    text-align:left;
    text-decoration:none
}
.multiselect-option.is-pointed{
    background:var(--ms-option-bg-pointed,#f3f4f6);
    color:var(--ms-option-color-pointed,#1f2937)
}
.multiselect-option.is-selected{
    background:var(--bs-primary,#10b981);
    color:var(--ms-option-color-selected,#fff)
}
.multiselect-option.is-disabled{
    background:var(--ms-option-bg-disabled,#fff);
    color:var(--ms-option-color-disabled,#d1d5db);
    cursor:not-allowed
}
.multiselect-option.is-selected.is-pointed{
    background:var(--bs-primary,#26c08e);
    color:var(--ms-option-color-selected-pointed,#fff)
}
.multiselect-option.is-selected.is-disabled{
    background:var(--ms-option-bg-selected-disabled,#87dcc0);
    color:var(--ms-option-color-selected-disabled,#d1fae5)
}
.multiselect-no-options,
.multiselect-no-results{
    color:var(--ms-empty-color,#4b5563);
    padding:var(--ms-option-py,.5rem) var(--ms-option-px,.75rem)
}
.multiselect-fake-input{
    background:transparent;
    border:0;
    bottom:-1px;
    font-size:0;
    height:1px;
    left:0;
    outline:none;
    padding:0;
    position:absolute;
    right:0;
    width:100%
}
.multiselect-fake-input:active,
.multiselect-fake-input:focus{
    outline:none
}
.multiselect-assistive-text{
    clip:rect(0 0 0 0);
    height:1px;
    margin:-1px;
    overflow:auto;
    position:absolute;
    width:1px
}
.multiselect-spacer{
    display:none
}
[dir=rtl] .multiselect-multiple-label,
[dir=rtl] .multiselect-placeholder,
[dir=rtl] .multiselect-single-label{
    left:auto;
    padding-left:calc(1.25rem + var(--ms-px, .875rem)*3);
    padding-right:var(--ms-px,.875rem);
    right:0
}
[dir=rtl] .multiselect-search{
    padding-left:0;
    padding-right:var(--ms-px,.875rem)
}
[dir=rtl] .multiselect-tags{
    padding-left:0;
    padding-right:var(--ms-py,.5rem)
}
[dir=rtl] .multiselect-tag{
    margin-left:var(--ms-tag-mx,.25rem);
    margin-right:0;
    padding:var(--ms-tag-py,.125rem) var(--ms-tag-px,.5rem) var(--ms-tag-py,.125rem) 0
}
[dir=rtl] .multiselect-tag.is-disabled{
    padding-left:var(--ms-tag-px,.5rem)
}
[dir=rtl] .multiselect-caret,
[dir=rtl] .multiselect-spinner{
    margin:0 0 0 var(--ms-px,.875rem)
}
[dir=rtl] .multiselect-clear{
    padding:0 0 0 var(--ms-px,.875rem)
}
@keyframes multiselect-spin{
    0%{
        transform:rotate(0)
    }
    to{
        transform:rotate(1turn)
    }
}
</style>