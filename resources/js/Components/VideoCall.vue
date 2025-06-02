<template>
    <div>
      <h2>Video Call</h2>
      <button v-if="!isCalling" @click="startCall">Start Call</button>
      <button v-if="isCalling" @click="endCall">End Call</button>
  
      <video ref="myVideo" autoplay playsinline></video>
      <video ref="peerVideo" autoplay playsinline></video>
    </div>
  </template>
  
  <script>
  import Peer from "peerjs";
  
  export default {
    data() {
      return {
        peer: null,
        myPeerId: null,
        call: null,
        isCalling: false,
      };
    },
    mounted() {
      // Connect to our self-hosted PeerJS server
      this.peer = new Peer({
        host: "localhost",
        port: 9000,  // Port of our PeerJS server
        path: "/peerjs",
        secure: false,  // Set to true if using HTTPS
      });
  
      this.peer.on("open", (id) => {
        console.log("My peer ID:", id);
        this.myPeerId = id;
      });
  
      // Listen for incoming calls
      this.peer.on("call", (call) => {
        navigator.mediaDevices
          .getUserMedia({ video: true, audio: true })
          .then((stream) => {
            call.answer(stream);
            this.isCalling = true;
  
            call.on("stream", (peerStream) => {
              this.$refs.peerVideo.srcObject = peerStream;
            });
  
            this.call = call;
          });
      });
    },
    methods: {
      startCall() {
        navigator.mediaDevices
          .getUserMedia({ video: true, audio: true })
          .then((stream) => {
            this.$refs.myVideo.srcObject = stream;
  
            const peerId = prompt("Enter the peer ID to call:");
            if (!peerId) return;
  
            const call = this.peer.call(peerId, stream);
            this.isCalling = true;
  
            call.on("stream", (peerStream) => {
              this.$refs.peerVideo.srcObject = peerStream;
            });
  
            this.call = call;
          });
      },
      endCall() {
        if (this.call) {
          this.call.close();
          this.isCalling = false;
        }
      },
    },
  };
  </script>
  
  <style>
  video {
    width: 300px;
    height: 200px;
    border: 2px solid black;
    margin: 10px;
  }
  </style>
  