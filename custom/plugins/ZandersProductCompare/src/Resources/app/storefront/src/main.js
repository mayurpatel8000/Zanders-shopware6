import ZandersProductComparePlugin from './plugin/zanders-product-compare.plugin';

const PluginManager = window.PluginManager;
if (PluginManager) {
    PluginManager.register('ZandersProductComparePlugin', ZandersProductComparePlugin, '[data-zanders-compare]');
}

