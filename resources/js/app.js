import './bootstrap';
import Alpine from 'alpinejs'
import collapse from '@alpinejs/collapse'

window.Alpine = Alpine

Alpine.plugin(collapse)

function parseJson(value, fallback) {
    try {
        return JSON.parse(value || '')
    } catch (e) {
        return fallback
    }
}

function toMoneyNumber(value) {
    const parsed = Number.parseFloat(value)

    return Number.isFinite(parsed) ? parsed : 0
}

function normalizeQuantities(values) {
    const source = values && typeof values === 'object' ? values : {}
    const normalized = {}

    for (const [itemId, quantity] of Object.entries(source)) {
        const parsed = Number.parseInt(quantity, 10)
        normalized[String(itemId)] = Number.isFinite(parsed) && parsed > 0 ? parsed : 0
    }

    return normalized
}

function normalizeScalarSelections(values) {
    const source = values && typeof values === 'object' ? values : {}
    const normalized = {}

    for (const [itemId, value] of Object.entries(source)) {
        normalized[String(itemId)] = value ? String(value) : ''
    }

    return normalized
}

function normalizeArraySelections(values) {
    const source = values && typeof values === 'object' ? values : {}
    const normalized = {}

    for (const [itemId, itemValues] of Object.entries(source)) {
        if (!Array.isArray(itemValues)) {
            normalized[String(itemId)] = []
            continue
        }

        normalized[String(itemId)] = itemValues
            .map(value => String(value))
            .filter(Boolean)
    }

    return normalized
}

const defaultPickupDetailsStorageKey = 'resto.preorder.pickup-details'

function normalizePickupDetails(values) {
    const source = values && typeof values === 'object' ? values : {}

    return {
        phone: typeof source.phone === 'string' ? source.phone : '',
        orderType: source.orderType === 'delivery' ? 'delivery' : 'pickup',
        address: typeof source.address === 'string' ? source.address : '',
        selectedLocation: source.selectedLocation ? String(source.selectedLocation) : '',
        selectedDate: source.selectedDate ? String(source.selectedDate) : '',
        selectedTime: source.selectedTime ? String(source.selectedTime) : '',
    }
}

window.pickupSelector = function (options) {
    const config = options || {}
    const menuItems = Array.isArray(config.menuItems) ? config.menuItems : []

    const menuItemsMap = menuItems.reduce((carry, item) => {
        if (!item || typeof item !== 'object') {
            return carry
        }

        const key = String(item.id || '')

        if (!key) {
            return carry
        }

        carry[key] = {
            id: item.id,
            title: item.title || 'Menu Item',
            price: toMoneyNumber(item.price),
            image_url: item.image_url || '',
            variants: Array.isArray(item.variants)
                ? item.variants.map(variant => ({
                    id: variant.id,
                    name: variant.name || '',
                    price: toMoneyNumber(variant.price),
                }))
                : [],
            addons: Array.isArray(item.addons)
                ? item.addons.map(addon => ({
                    id: addon.id,
                    name: addon.name || '',
                    price: toMoneyNumber(addon.price),
                }))
                : [],
        }

        return carry
    }, {})

    return {
        search: '',
        step: Number.parseInt(config.initialStep || 1, 10) === 2 ? 2 : 1,
        mobileCartOpen: false,
        cartError: '',
        detailsError: '',
        availability: config.availability || {},
        menuItems,
        menuItemsMap,
        quantities: normalizeQuantities(config.initialQuantities),
        selectedVariants: normalizeScalarSelections(config.initialVariantIds),
        selectedAddons: normalizeArraySelections(config.initialAddonIds),
        pickupDetailsStorageKey: typeof config.pickupDetailsStorageKey === 'string' && config.pickupDetailsStorageKey
            ? config.pickupDetailsStorageKey
            : defaultPickupDetailsStorageKey,
        phone: typeof config.initialPhone === 'string' ? config.initialPhone : '',
        orderType: config.initialOrderType === 'delivery' ? 'delivery' : 'pickup',
        forceOrderType: config.forceOrderType || null,
        address: '',
        pastAddresses: [],
        selectedLocation: config.initialLocation ? String(config.initialLocation) : '',
        selectedDate: config.initialDate ? String(config.initialDate) : '',
        selectedTime: config.initialTime ? String(config.initialTime) : '',
        match(title, description) {
            const q = (this.search || '').toLowerCase().trim()

            if (!q) {
                return true
            }

            return (title || '').toLowerCase().includes(q) || (description || '').toLowerCase().includes(q)
        },
        init() {
            this.restorePickupDetails()
            if (this.forceOrderType) {
                this.orderType = this.forceOrderType
            }
            this.onLocationChange()
            this.registerPickupDetailsWatchers()
            this.persistPickupDetails()
            
            if (this.phone) {
                this.fetchPastAddresses()
            }
        },
        pickupDetails() {
            return normalizePickupDetails({
                phone: this.phone,
                orderType: this.orderType,
                address: this.address,
                selectedLocation: this.selectedLocation,
                selectedDate: this.selectedDate,
                selectedTime: this.selectedTime,
            })
        },
        restorePickupDetails() {
            if (!this.pickupDetailsStorageKey) {
                return
            }

            let storedPickupDetails = {}

            try {
                storedPickupDetails = normalizePickupDetails(
                    parseJson(window.localStorage.getItem(this.pickupDetailsStorageKey), {})
                )
            } catch (error) {
                storedPickupDetails = {}
            }

            if (!(this.phone || '').trim() && storedPickupDetails.phone) {
                this.phone = storedPickupDetails.phone
            }

            if (!this.selectedLocation && storedPickupDetails.selectedLocation) {
                this.selectedLocation = storedPickupDetails.selectedLocation
            }

            if (!this.selectedDate && storedPickupDetails.selectedDate) {
                this.selectedDate = storedPickupDetails.selectedDate
            }

            if (!this.selectedTime && storedPickupDetails.selectedTime) {
                this.selectedTime = storedPickupDetails.selectedTime
            }

            if (storedPickupDetails.orderType) {
                this.orderType = storedPickupDetails.orderType
            }

            if (!this.address && storedPickupDetails.address) {
                this.address = storedPickupDetails.address
            }
        },
        persistPickupDetails() {
            if (!this.pickupDetailsStorageKey) {
                return
            }

            const pickupDetails = this.pickupDetails()
            const hasPickupDetails = Object.values(pickupDetails)
                .some(value => String(value || '').trim() !== '')

            try {
                if (!hasPickupDetails) {
                    window.localStorage.removeItem(this.pickupDetailsStorageKey)
                    return
                }

                window.localStorage.setItem(this.pickupDetailsStorageKey, JSON.stringify(pickupDetails))
            } catch (error) {
                // Ignore storage write failures and keep the form usable.
            }
        },
        registerPickupDetailsWatchers() {
            this.$watch('phone', () => {
                this.persistPickupDetails()
                this.fetchPastAddresses()
            })
            this.$watch('orderType', () => this.persistPickupDetails())
            this.$watch('address', () => this.persistPickupDetails())
            this.$watch('selectedLocation', () => this.persistPickupDetails())
            this.$watch('selectedDate', () => this.persistPickupDetails())
            this.$watch('selectedTime', () => this.persistPickupDetails())
        },
        async fetchPastAddresses() {
            const phone = (this.phone || '').trim()
            if (phone.length < 5) {
                this.pastAddresses = []
                return
            }

            try {
                const response = await fetch(`/past-addresses?phone=${encodeURIComponent(phone)}`)
                if (response.ok) {
                    this.pastAddresses = await response.json()
                }
            } catch (e) {
                console.error('Failed to fetch past addresses', e)
            }
        },
        itemKey(itemId) {
            return String(itemId || '')
        },
        quantityFor(itemId) {
            const key = this.itemKey(itemId)
            const quantity = Number.parseInt(this.quantities[key] || 0, 10)

            return Number.isFinite(quantity) && quantity > 0 ? quantity : 0
        },
        setQuantity(itemId, quantity) {
            const key = this.itemKey(itemId)
            const parsed = Number.parseInt(quantity || 0, 10)
            this.quantities[key] = Number.isFinite(parsed) && parsed > 0 ? parsed : 0

            if (this.quantityFor(itemId) === 0) {
                this.selectedVariants[key] = this.selectedVariants[key] || ''
                this.selectedAddons[key] = this.selectedAddons[key] || []
            }

            this.cartError = ''
        },
        increaseQuantity(itemId) {
            this.setQuantity(itemId, this.quantityFor(itemId) + 1)
        },
        decreaseQuantity(itemId) {
            this.setQuantity(itemId, Math.max(0, this.quantityFor(itemId) - 1))
        },
        ensureAddonSelection(itemId) {
            const key = this.itemKey(itemId)

            if (!Array.isArray(this.selectedAddons[key])) {
                this.selectedAddons[key] = []
            }

            return this.selectedAddons[key]
        },
        selectedVariantFor(item) {
            const key = this.itemKey(item.id)
            const selectedVariantId = this.selectedVariants[key] ? String(this.selectedVariants[key]) : ''

            if (!selectedVariantId) {
                return null
            }

            return (item.variants || []).find(variant => String(variant.id) === selectedVariantId) || null
        },
        selectedAddonsFor(item) {
            const selectedAddonIds = new Set(this.ensureAddonSelection(item.id).map(addonId => String(addonId)))

            return (item.addons || []).filter(addon => selectedAddonIds.has(String(addon.id)))
        },
        unitPriceFor(item) {
            const selectedVariant = this.selectedVariantFor(item)
            const basePrice = selectedVariant ? toMoneyNumber(selectedVariant.price) : toMoneyNumber(item.price)
            const addonsPrice = this.selectedAddonsFor(item)
                .reduce((total, addon) => total + toMoneyNumber(addon.price), 0)

            return basePrice + addonsPrice
        },
        lineTotalFor(itemId) {
            const key = this.itemKey(itemId)
            const item = this.menuItemsMap[key]

            if (!item) {
                return 0
            }

            return this.quantityFor(itemId) * this.unitPriceFor(item)
        },
        cartItems() {
            return this.menuItems
                .map(rawItem => {
                    const itemId = String(rawItem?.id || '')
                    const item = this.menuItemsMap[itemId]

                    if (!item) {
                        return null
                    }

                    const quantity = this.quantityFor(item.id)

                    if (quantity < 1) {
                        return null
                    }

                    const selectedVariant = this.selectedVariantFor(item)
                    const selectedAddons = this.selectedAddonsFor(item)

                    return {
                        id: item.id,
                        title: item.title,
                        quantity,
                        imageUrl: item.image_url || '',
                        variantName: selectedVariant?.name || null,
                        addonNames: selectedAddons.map(addon => addon.name),
                        lineTotal: this.lineTotalFor(item.id),
                    }
                })
                .filter(Boolean)
        },
        cartItemCount() {
            return this.cartItems().reduce((total, item) => total + item.quantity, 0)
        },
        cartSubtotal() {
            return this.cartItems().reduce((total, item) => total + item.lineTotal, 0)
        },
        formatMoney(amount) {
            return `ETB ${Math.round(toMoneyNumber(amount)).toLocaleString()}`
        },
        goToDetails() {
            if (this.cartItemCount() < 1) {
                this.cartError = 'Please add at least one item before continuing.'
                return
            }

            this.cartError = ''
            this.step = 2
        },
        backToMenu() {
            this.step = 1
            this.detailsError = ''
        },
        detailsComplete() {
            const hasPhone = Boolean((this.phone || '').trim())
            
            if (this.orderType === 'delivery') {
                return hasPhone && Boolean((this.address || '').trim())
            }

            return hasPhone
                && Boolean(this.selectedLocation)
                && Boolean(this.selectedDate)
                && Boolean(this.selectedTime)
        },
        canSubmit() {
            return this.cartItemCount() > 0 && this.detailsComplete()
        },
        openMobileCart() {
            this.mobileCartOpen = true
        },
        closeMobileCart() {
            this.mobileCartOpen = false
        },
        placePreorder() {
            if (this.cartItemCount() < 1) {
                this.cartError = 'Please add at least one item before placing the preorder.'
                this.step = 1
                this.mobileCartOpen = true
                return
            }

            if (!this.detailsComplete()) {
                this.detailsError = this.orderType === 'delivery'
                    ? 'Please provide your phone and delivery address.'
                    : 'Please fill your phone, pickup location, date, and time.'
                this.step = 2
                this.mobileCartOpen = true
                return
            }

            this.cartError = ''
            this.detailsError = ''

            const form = this.$root instanceof HTMLFormElement
                ? this.$root
                : this.$el?.closest?.('form')

            if (!form) {
                return
            }

            if (typeof form.requestSubmit === 'function') {
                form.requestSubmit()
                return
            }

            form.submit()
        },
        slotsForLocation() {
            const key = String(this.selectedLocation || '')
            return this.availability[key] || []
        },
        dateOptions() {
            const seen = new Set()
            const options = []

            for (const slot of this.slotsForLocation()) {
                if (!seen.has(slot.date)) {
                    seen.add(slot.date)
                    options.push({
                        value: slot.date,
                        label: slot.date_label,
                    })
                }
            }

            return options
        },
        timeOptions() {
            if (!this.selectedDate) {
                return []
            }

            return this.slotsForLocation()
                .filter(slot => slot.date === this.selectedDate)
                .map(slot => ({
                    value: slot.time,
                    label: slot.time_label,
                }))
        },
        onLocationChange() {
            const dates = this.dateOptions()
            const hasDate = dates.some(option => option.value === this.selectedDate)

            if (!hasDate) {
                this.selectedDate = dates[0] ? dates[0].value : ''
            }

            this.onDateChange()
        },
        onDateChange() {
            const times = this.timeOptions()
            const hasTime = times.some(option => option.value === this.selectedTime)

            if (!hasTime) {
                this.selectedTime = times[0] ? times[0].value : ''
            }
        },
    }
}

window.pickupSelectorFromEl = function (el) {
    const availability = parseJson(el?.dataset?.pickupAvailability, {})
    const menuItems = parseJson(el?.dataset?.menuItems, [])
    const initialQuantities = parseJson(el?.dataset?.oldQuantities, {})
    const initialVariantIds = parseJson(el?.dataset?.oldVariantIds, {})
    const initialAddonIds = parseJson(el?.dataset?.oldAddonIds, {})

    return window.pickupSelector({
        availability,
        menuItems,
        initialQuantities,
        initialVariantIds,
        initialAddonIds,
        initialLocation: el?.dataset?.oldLocation || null,
        initialDate: el?.dataset?.oldDate || null,
        initialTime: el?.dataset?.oldTime || null,
        initialPhone: el?.dataset?.oldPhone || '',
        pickupDetailsStorageKey: el?.dataset?.pickupDetailsStorageKey || defaultPickupDetailsStorageKey,
        initialStep: el?.dataset?.initialStep || '1',
        initialOrderType: el?.dataset?.initialOrderType || null,
        forceOrderType: el?.dataset?.forceOrderType || null,
    })
}

Alpine.start()
