// The file contents for the current environment will overwrite these during build.
// The build system defaults to the dev environment which uses `environment.ts`, but if you do
// `ng build --env=prod` then `environment.prod.ts` will be used instead.
// The list of which env maps to which file can be found in `.angular-cli.json`.

export const environment = {
    production: false,
    host: 'http://imusify.loc',
    creds: 'ajax=true',
    assets: '/',
    login: '/api/login',
    signup: '/api/signup',
    ulogout: '/ulogout',
    checkAuth: '/api/user/check-auth',
    uploadTrackList: '/upload',
    uploadFilesUrl: '/api/track-upload/upload-track-file',
    musicList: '/music',
    uploadTrackImage: '/crop/index/trackImg',
    uploadDetails: '/upload_details',
    saveTrack: '/save_track',
    browse: '/browse',
    browseNewSongs:  '/browse/new-songs',
    browsePopularArtist: '/browse/popular-artist'
};
