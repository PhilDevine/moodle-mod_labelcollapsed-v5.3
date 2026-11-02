
module.exports = function(grunt) {
    grunt.loadNpmTasks('grunt-contrib-requirejs');

    grunt.initConfig({
        requirejs: {
            amd: {
                options: {
                    baseUrl: 'amd/src',
                    name: 'toggler',
                    out: 'amd/build/toggler.min.js',
                    optimize: 'uglify2',
                    paths: {
                        toggler: './toggler'
                    },
                    wrap: false
                }
            }
        }
    });

    grunt.registerTask('amd', ['requirejs:amd']);
    grunt.registerTask('default', ['amd']);
};
