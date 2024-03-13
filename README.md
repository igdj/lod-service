Linked Open Data Service
========================

This is the initial code for factoring out the LOD Services
accessed in the Digital Source Edition Key Documents of German-Jewish History

* [The Integrated Authority File (GND)](https://www.dnb.de/EN/Professionell/Standardisierung/GND/gnd_node.html)
    of the Deutsche Nationalbibliothek
* [Getty Thesaurus of Geographic Names (TGN)](https://www.getty.edu/research/tools/vocabularies/)

You may use it in parts or adjust it to your own need if it fits your needs.
If you have any questions or find this code helpful, please contact us at
    https://keydocuments.net/contact

Status
------
This library is still very much work in progress.

* Currently, only a limited set of properties is implemented in the Model-classes.
* We aim to support multilingual labels and various date formats by switching to [data-values/time](https://packagist.org/packages/data-values/time) and multilingual text value from [data-values/common](https://packagist.org/packages/data-values/common).

Requirements
------------

* PHP 7.3 or higher

License
-------
The library are licensed under the [BSD-3-Clause] license.

(C) 2017-2024 Institut für die Geschichte der deutschen Juden,
    Daniel Burckhardt

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.

2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

3. Neither the name of the copyright holder nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

[BSD-3-Clause]:https://www.opensource.org/licenses/BSD-3-Clause
