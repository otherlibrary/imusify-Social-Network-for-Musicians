import {IUser} from "./IUser";
import {IArtist} from "./IArtist";


export interface IArtistData extends IUser {
  img: string
  never_sell: string
  new_artist_active: string
  new_playlist_active: string
  new_songs_active: string
  popular_artist_active: string
  popular_playlist_active: string
  popular_songs_active: string
  popular_users: IArtist[]
  data_array: IArtist[]
  profileImage: string
  profileLink: string
  recommended_page: string
  title: string
  url: string
}