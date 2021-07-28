import { Controller } from 'stimulus';
import addParcelforms from './../js/addParcelforms';

export default class extends Controller {
    connect() {
        addParcelforms(3);
    }
}
