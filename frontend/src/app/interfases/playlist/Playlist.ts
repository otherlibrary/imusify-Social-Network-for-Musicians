export class Playlist {
  constructor(public id: string,
              public comments: string,
              public createdDate: string,
              public like: string,
              public name: string,
              public playlistImage: string,
              public plays: string,
              public share: string,
              public status: string,
              public updatedDate: string,
              public userId: string,) {
  }
}

export interface IPlaylists {
  currentId: string;
  loggedin: boolean;
  playlist: Playlist[]
}