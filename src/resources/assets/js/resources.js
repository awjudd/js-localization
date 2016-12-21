'use strict';

window.Resources = (function(){
    /**
     * All of the resources loaded into the system
     *
     * @type       Array
     */
    var data = [];

    /**
     * Gets a specific resource for the user.
     *
     * @param      string  key           The key to look for
     * @param      object  replacements  The values to replace
     * @return     mixed   The resource value with any replacements completed.
     */
    var get = function(key, replacements) {
        var resource = baseResource(key);
        return replacePlaceholders(resource, replacements);
    };

    /**
     * Helper to derive the resource name
     *
     * @param      string   key     The key
     * @return     mixed  The resource value, or the key if not found.
     */
    var baseResource = function(key) {
        var resource = findResource(key, Resources.data);

        return resource === undefined ? key : resource;
    };

    /**
     * Recursively finds the resource related to the data provided.
     *
     * @param      {string}  key     The key
     * @param      {<type>}  data    The data
     * @return     {<type>}  { description_of_the_return_value }
     */
    var findResource = function (key, data) {
        // Is there a '.'?
        if(key.indexOf('.') == -1) {
            // There isn't, so return the value
            return data[key];
        }

        // Split the string up around the first '.''
        var attribute = key.substring(0, key.indexOf('.'));
        var remainder = key.substring(key.indexOf('.') + 1);

        // Either recursively find the resoruce, or the data.
        return typeof(data[attribute]) == 'object' ? findResource(remainder, data[attribute]) : data[attribute];
    }

    /**
     * Replaces any placeholders in the text with the requested replacements.
     *
     * @param      mixed  resource      The resource
     * @param      object    replacements  The replacements
     * @return     {Function}  { description_of_the_return_value }
     */
    var replacePlaceholders = function(resource, replacements) {
        if(typeof(resource) == 'object') {
            return resource;
        }

        for(var index in replacements) {
            resource = replaceAll(resource, ':' + index, replacements[index]);
        };

        return resource;
    };

    /**
     * Replaces all instances of an key with the related value
     *
     * @param      string  resource     The resource
     * @param      stiring  expression  The expression to replace
     * @param      string  value        The value to replace it with
     * @return     {string}  { description_of_the_return_value }
     */
    var replaceAll = function(resource, expression, value) {
        return resource.replace(new RegExp(expression.replace(/[.?*+^$[\]\\(){}|-]/g, "\\$&"), 'g'), value);
    }

    return {
        data: data,
        get: get
    };
})();