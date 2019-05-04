Nova.booting((Vue, router, store) => {
    router.addRoutes([
        {
            name: 'invoice-adjustment',
            path: '/invoice-adjustment',
            component: require('./components/Tool'),
        },
    ])
})
