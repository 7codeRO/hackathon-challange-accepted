import axios from 'axios';

export default new class {
    list(params){
        return axios.post('/comments/for-question', {question_id: params.question_id});
    }
    store(data){
        return axios.post('/comments', data);
    }
    like(id) {
        return axios.get(`/comments/${id}/like`);
    }
    dislike(id) {
        return axios.get(`/comments/${id}/dislike`);
    }

    answer(id) {
        return axios.get(`/comments/${id}/answer`);
    }

}